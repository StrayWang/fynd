using System;
using System.Collections.Generic;
using System.Text;
using System.Data.Common;
using FyndSharp.Utilities.Common;
using System.Reflection;
using System.Data;
using System.Collections;

namespace FyndSharp.Data.Orm
{
    /// <summary>
    /// ORM对象工厂，负责System.Data.Common.DbCommand、System.Data.DataTable和ORM对象之间的转换。
    /// </summary>
    public class OrmObjectFactory
    {
        private static readonly Type _TableAttributeType = typeof(TableAttribute);
        private static readonly Type _FieldAttributeType = typeof(FieldAttribute);
        private static readonly Hashtable _TableInfoCache = Hashtable.Synchronized(new Hashtable());
        /// <summary>
        /// 表示获取下一个有效主键值的代理方法
        /// </summary>
        /// <returns></returns>
        public delegate object NextPrimaryValueHandler();
        /// <summary>
        /// 获取下一个有效主键值的代理方法
        /// </summary>
        protected NextPrimaryValueHandler PrimaryValueHandler;
        /// <summary>
        /// 获取或设置数据库会话对象
        /// </summary>
        public AdoSession DbSession { get; set; }
        /// <summary>
        /// 创建OrmObjectFactory新实例
        /// </summary>
        /// <param name="theNextPrimaryValueHandler">负责获取下一个有效主键值的代理方法</param>
        /// <param name="theDbSession">OrmObjectFactory要使用的数据库会话对象</param>
        public OrmObjectFactory(NextPrimaryValueHandler theNextPrimaryValueHandler, AdoSession theDbSession)
        {
            PrimaryValueHandler = theNextPrimaryValueHandler;
            DbSession = theDbSession;
        }
        /// <summary>
        /// 创建用于向数据源写入数据的DbCommand，如果指定的object对应的主键值为设置，则创建INSERT，反之创建UPDATE
        /// </summary>
        /// <param name="obj"></param>
        /// <returns></returns>
        public DbCommand CreateSaveDbCommand(object obj)
        {
            Type theType = obj.GetType();
            TableInfo theTableInfo = CreateTableInfo(theType);

            object thePrimaryValue = theTableInfo.Primary.Property.GetValue(obj, null);
            if (null == thePrimaryValue || String.IsNullOrEmpty(thePrimaryValue.ToString()))
            {
                // TODO Insert
                return CreateInsertCommand(obj, theTableInfo);
            }
            else
            {
                // TODO Update
                return CreateUpdateCommand(obj, theTableInfo);
            }
        }

        private static TableInfo CreateTableInfo(Type theType)
        {
            TableInfo theTableInfo = null;
            if (_TableInfoCache.ContainsKey(theType.FullName))
            {
                theTableInfo = _TableInfoCache[theType.FullName] as TableInfo;
                if (null == theTableInfo)
                {
                    _TableInfoCache.Remove(theType.FullName);
                }
            }
            if (null != theTableInfo)
            {
                return theTableInfo;
            }
            object[] tableAttrs = theType.GetCustomAttributes(_TableAttributeType, true);

            Checker.Assert<ArgumentException>(tableAttrs.Length > 0, "The class of 'obj' should contain FyndSharp.Data.Orm.TableAttribute.");
            // TODO cache property infos
            theTableInfo = new TableInfo();
            theTableInfo.TableAttribute = (TableAttribute)tableAttrs[0];

            PropertyInfo[] properties = theType.GetProperties();

            Checker.Assert<ArgumentException>(properties.Length > 0, "The class of 'obj' should contain one property at least.");

            FieldAttribute theCurrentFeild = null;
            object[] fieldAttrs = null;

            foreach (PropertyInfo aProperty in properties)
            {
                fieldAttrs = aProperty.GetCustomAttributes(_FieldAttributeType, true);
                if (fieldAttrs.Length <= 0)
                {
                    continue;
                }
                theCurrentFeild = (FieldAttribute)fieldAttrs[0];
                if (theCurrentFeild.IsPrimary)
                {
                    theTableInfo.Primary = new FieldInfo()
                    {
                        FeildAttribute = theCurrentFeild,
                        Property = aProperty
                    };
                }
                else
                {
                    theTableInfo.FieldList.Add(new FieldInfo()
                    {
                        FeildAttribute = theCurrentFeild,
                        Property = aProperty
                    });
                }
            }
            _TableInfoCache.Add(theType.FullName, theTableInfo);
            return theTableInfo;
        }
        private DbCommand CreateUpdateCommand(object obj, TableInfo theTableInfo)
        {
            // 检查表信息是否合法
            CheckTable(theTableInfo);

            StringBuilder builder = new StringBuilder("UPDATE ");
            builder.Append(theTableInfo.TableAttribute.TableName);
            builder.Append(" SET ");
            int i = 0;
            List<DbParameter> theDbParams = new List<DbParameter>(theTableInfo.FieldList.Count + 1);
            // 遍历所有非主键字段，创建SET子句及其参数
            foreach (FieldInfo aFeild in theTableInfo.FieldList)
            {
                if (i > 0)
                {
                    builder.Append(",");
                }
                // 检查字段信息是否合法
                CheckField(aFeild);

                builder.Append(aFeild.FeildAttribute.FieldName);
                builder.Append("=@");
                builder.Append(aFeild.FeildAttribute.FieldName);
                // 创建参数对象
                theDbParams.Add(CreateDbParameter(obj, aFeild));
                i++;
            }
            
            builder.Append(" WHERE ");
            builder.Append(theTableInfo.Primary.FeildAttribute.FieldName);
            builder.Append("=@");
            builder.Append(theTableInfo.Primary.FeildAttribute.FieldName);
            // 创建主键参数
            theDbParams.Add(CreateDbParameter(obj, theTableInfo.Primary));

            return DbSession.CreateCommand(builder.ToString(), theDbParams.ToArray());
        }

        private static void CheckTable(TableInfo theTableInfo)
        {
            Checker.Assert<ArgumentException>((null != theTableInfo.Primary)
                    && (null != theTableInfo.Primary.FeildAttribute)
                    && !String.IsNullOrEmpty(theTableInfo.Primary.FeildAttribute.FieldName)
                    && (theTableInfo.FieldList.Count > 0)
                , "The table is invalid.");
        }
        private static void CheckField(FieldInfo aFeild)
        {
            Checker.Assert<ArgumentException>(null != aFeild
                        && null != aFeild.FeildAttribute
                        && !String.IsNullOrEmpty(aFeild.FeildAttribute.FieldName)
                    , "The feild is invalid.");
        }
        private DbParameter CreateDbParameter(object obj, FieldInfo aFeild)
        {
            Checker.Assert<ArgumentNullException>(null != aFeild);

            object theFeildValue = aFeild.Property.GetValue(obj, null);
            if (aFeild.FeildAttribute.IsPrimary && null == theFeildValue)
            {
                // 获取主键值
                theFeildValue = PrimaryValueHandler();
                Checker.Assert<NotSupportedException>(theFeildValue != null, "Can not set the primary to null.");
            }
            else
            {
                Checker.Assert<ArgumentException>(aFeild.FeildAttribute.AllowNull || (!aFeild.FeildAttribute.AllowNull && null != theFeildValue)
                    , String.Format("The field {0} can not be null.", aFeild.FeildAttribute.FieldName));
                if (null == theFeildValue)
                {
                    theFeildValue = DBNull.Value;
                }
            }
            return this.DbSession.CreateParameter("@" + aFeild.FeildAttribute.FieldName, aFeild.FeildAttribute.DataType, theFeildValue);
        }
        private DbCommand CreateInsertCommand(object obj, TableInfo theTableInfo)
        {
            // 检查表信息是否合法
            CheckTable(theTableInfo);

            List<DbParameter> theDbParams = new List<DbParameter>(theTableInfo.FieldList.Count + 1);
            StringBuilder builder = new StringBuilder("INSERT INTO ");
            builder.Append(theTableInfo.TableAttribute.TableName);
            builder.Append(" (");
            // 主键及其参数
            StringBuilder colList = new StringBuilder(theTableInfo.Primary.FeildAttribute.FieldName);
            StringBuilder valList = new StringBuilder("@");
            valList.Append(theTableInfo.Primary.FeildAttribute.FieldName);
            theDbParams.Add(this.CreateDbParameter(obj, theTableInfo.Primary));
            // INSERT子句非主键column列表和参数
            foreach (FieldInfo aFeild in theTableInfo.FieldList)
            {
                colList.Append(",");
                colList.Append(aFeild.FeildAttribute.FieldName);
                valList.Append(",");
                valList.Append("@");
                valList.Append(aFeild.FeildAttribute.FieldName);
                theDbParams.Add(CreateDbParameter(obj, aFeild));
            }
            builder.Append(colList);
            builder.Append(") VALUES (");
            builder.Append(valList);
            builder.Append(")");

            return DbSession.CreateCommand(builder.ToString(), theDbParams.ToArray());
        }
        /// <summary>
        /// 根据ORM对象类型创建基本的SELECT语句，如SELECT f1, f2 FROM table1
        /// </summary>
        /// <param name="theType"></param>
        /// <returns></returns>
        public static string CreateSelectSql(Type theType)
        {
            Checker.Assert<ArgumentNullException>(null != theType);

            TableInfo theTableInfo = CreateTableInfo(theType);

            CheckTable(theTableInfo);

            StringBuilder builder = new StringBuilder("SELECT ");
            CheckField(theTableInfo.Primary);
            builder.Append(theTableInfo.Primary.FeildAttribute.FieldName);
            foreach (FieldInfo aFeild in theTableInfo.FieldList)
            {
                builder.Append(",");
                // 检查字段信息是否合法
                CheckField(aFeild);
                builder.Append(aFeild.FeildAttribute.FieldName);
            }
            
            builder.Append(" FROM ");
            builder.Append(theTableInfo.TableAttribute.TableName);
            return builder.ToString();
        }
        /// <summary>
        /// 创建类为T的对象，并将data中的数据按类型T映射关系绑定到该对象。
        /// </summary>
        /// <typeparam name="T">ORM对象类型</typeparam>
        /// <param name="data">数据行</param>
        /// <returns></returns>
        public static T CreateOrmObject<T>(DataRow data)
        {
            Type theType = typeof(T);
            TableInfo theTableInfo = CreateTableInfo(theType);
            CheckTable(theTableInfo);
            T obj = default(T);
            obj = (T)theType.Assembly.CreateInstance(theType.FullName);
            foreach (FieldInfo aFeild in theTableInfo.FieldList)
            {
                CheckField(aFeild);
                aFeild.Property.SetValue(obj, data[aFeild.FeildAttribute.FieldName], null);
            }
            CheckField(theTableInfo.Primary);
            theTableInfo.Primary.Property.SetValue(obj, data[theTableInfo.Primary.FeildAttribute.FieldName], null);
            return obj;
        }

        private class FieldInfo
        {
            public PropertyInfo Property;
            public FieldAttribute FeildAttribute;
        }
        private class TableInfo
        {
            public TableAttribute TableAttribute;
            public FieldInfo Primary;
            public List<FieldInfo> FieldList = new List<FieldInfo>();
        }
    }
}
