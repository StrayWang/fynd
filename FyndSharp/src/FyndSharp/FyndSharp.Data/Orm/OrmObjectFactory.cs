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
        /// <exception cref="System.ArgumentException"></exception>
        /// <exception cref="System.ArgumentNullException"></exception>
        /// <exception cref="System.NotSupportedException"></exception>
        public DbCommand CreateSaveDbCommand(object obj)
        {
            Type theType = obj.GetType();
            TableInfo theTableInfo = CreateTableInfo(theType);

            object thePrimaryValue = theTableInfo.Primary.Property.GetValue(obj, null);
            if (null == thePrimaryValue 
                || String.IsNullOrEmpty(thePrimaryValue.ToString())
                || (
                           (
                                  theTableInfo.Primary.Property.PropertyType.Equals(typeof(int))
                               || theTableInfo.Primary.Property.PropertyType.Equals(typeof(long))
                           )
                        && thePrimaryValue.Equals(0)
                   )
               )
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
        /// <summary>
        /// 通过类型创建插入命令
        /// </summary>
        /// <param name="theType"></param>
        /// <returns></returns>
        /// <exception cref="System.ArgumentException"></exception>
        /// <exception cref="System.ArgumentNullException"></exception>
        public DbCommand CreateInsertCommand(Type theType)
        {            
            return CreateInsertCommand(null, CreateTableInfo(theType));
        }
        /// <summary>
        /// 通过类型创建更新命令
        /// </summary>
        /// <param name="theType"></param>
        /// <returns></returns>
        /// <exception cref="System.ArgumentException"></exception>
        /// <exception cref="System.ArgumentNullException"></exception>
        public DbCommand CreateUpdateCommand(Type theType)
        {
            return CreateUpdateCommand(null, CreateTableInfo(theType));
        }
        /// <summary>
        /// 通过类型创建删除命令
        /// </summary>
        /// <param name="theType"></param>
        /// <returns></returns>
        /// <exception cref="System.ArgumentException"></exception>
        /// <exception cref="System.ArgumentNullException"></exception>
        public DbCommand CreateDeleteCommand(Type theType)
        {
            return CreateDeleteCommand(null, CreateTableInfo(theType));
        }
        /// <summary>
        /// 创建用于删除主键值对应记录的的DbCommand
        /// </summary>
        /// <param name="obj"></param>
        /// <returns></returns>
        /// <exception cref="System.ArgumentException"></exception>
        /// <exception cref="System.ArgumentNullException"></exception>
        /// <exception cref="System.NotSupportedException"></exception>
        public DbCommand CreateDeleteDbCommand(object obj)
        {
            Type theType = obj.GetType();
            TableInfo theTableInfo = CreateTableInfo(theType);
            return CreateDeleteCommand(obj, theTableInfo);
        }

        private DbCommand CreateDeleteCommand(object obj, TableInfo theTableInfo)
        {
            // 检查表信息是否合法
            CheckTable(theTableInfo);

            StringBuilder builder = new StringBuilder("DELETE FROM ");
            builder.Append(theTableInfo.TableAttribute.TableName);

            builder.Append(" WHERE ");
            builder.Append(theTableInfo.Primary.FieldAttribute.FieldName);
            builder.Append("=@");
            builder.Append(theTableInfo.Primary.FieldAttribute.FieldName);
            List<DbParameter> theDbParams = new List<DbParameter>(1);
            // 创建主键参数
            theDbParams.Add(CreateDbParameter(obj, theTableInfo.Primary));

            return DbSession.CreateCommand(builder.ToString(), theDbParams.ToArray());
        }
        /// <exception cref="System.ArgumentException"></exception>
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
                        FieldAttribute = theCurrentFeild,
                        Property = aProperty
                    };
                }
                else
                {
                    theTableInfo.FieldList.Add(new FieldInfo()
                    {
                        FieldAttribute = theCurrentFeild,
                        Property = aProperty
                    });
                }
            }
            _TableInfoCache.Add(theType.FullName, theTableInfo);
            return theTableInfo;
        }
        /// <summary>
        /// 创建UPDATE语句
        /// </summary>
        /// <param name="obj"></param>
        /// <param name="theTableInfo"></param>
        /// <returns></returns>
        /// <exception cref="System.ArgumentException"></exception>
        /// <exception cref="System.ArgumentNullException"></exception>
        /// <exception cref="System.NotSupportedException"></exception>
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

                builder.Append(aFeild.FieldAttribute.FieldName);
                builder.Append("=@");
                builder.Append(aFeild.FieldAttribute.FieldName);
                // 创建参数对象
                theDbParams.Add(CreateDbParameter(obj, aFeild));
                i++;
            }
            
            builder.Append(" WHERE ");
            builder.Append(theTableInfo.Primary.FieldAttribute.FieldName);
            builder.Append("=@");
            builder.Append(theTableInfo.Primary.FieldAttribute.FieldName);
            // 创建主键参数
            theDbParams.Add(CreateDbParameter(obj, theTableInfo.Primary));

            return DbSession.CreateCommand(builder.ToString(), theDbParams.ToArray());
        }
        /// <summary>
        /// 检查表信息对象，判断主键和其他字段信息是否存在
        /// </summary>
        /// <param name="theTableInfo"></param>
        /// <exception cref="System.ArgumentException"></exception>
        private static void CheckTable(TableInfo theTableInfo)
        {
            Checker.Assert<ArgumentException>((null != theTableInfo.Primary)
                    && (null != theTableInfo.Primary.FieldAttribute)
                    && !String.IsNullOrEmpty(theTableInfo.Primary.FieldAttribute.FieldName)
                    && (theTableInfo.FieldList.Count > 0)
                , "The table is invalid.");
        }
        /// <summary>
        /// 检查字段信息对象，判断字段名称是否为空
        /// </summary>
        /// <param name="aFeild"></param>
        /// <exception cref="System.ArgumentException"></exception>
        private static void CheckField(FieldInfo aFeild)
        {
            Checker.Assert<ArgumentException>(null != aFeild
                        && null != aFeild.FieldAttribute
                        && !String.IsNullOrEmpty(aFeild.FieldAttribute.FieldName)
                    , "The feild is invalid.");
        }
        /// <summary>
        /// 创建DbParamater
        /// </summary>
        /// <param name="obj"></param>
        /// <param name="aFeild"></param>
        /// <returns></returns>
        /// <exception cref="System.ArgumentException"></exception>
        /// <exception cref="System.ArgumentNullException"></exception>
        /// <exception cref="System.NotSupportedException"></exception>
        private DbParameter CreateDbParameter(object obj, FieldInfo aFeild)
        {
            Checker.Assert<ArgumentNullException>(null != aFeild);

            object theFeildValue = null;
            if (null != obj)
            {
                theFeildValue = aFeild.Property.GetValue(obj, null);
                if (
                           (aFeild.FieldAttribute.IsPrimary && null == theFeildValue)
                        || (
                                   aFeild.FieldAttribute.IsPrimary 
                                && (
                                           aFeild.Property.PropertyType.Equals(typeof(int)) 
                                        || aFeild.Property.PropertyType.Equals(typeof(long))
                                   )
                                && theFeildValue.Equals(0)
                           )
                   )
                {
                    // 获取主键值
                    theFeildValue = PrimaryValueHandler();
                    Checker.Assert<NotSupportedException>(theFeildValue != null, "Can not set the primary to null.");
                }
                else
                {
                    Checker.Assert<ArgumentException>(aFeild.FieldAttribute.AllowNull || (!aFeild.FieldAttribute.AllowNull && null != theFeildValue)
                        , String.Format("The field {0} can not be null.", aFeild.FieldAttribute.FieldName));
                    if (null == theFeildValue)
                    {
                        theFeildValue = DBNull.Value;
                    }
                }
            }
            return this.DbSession.CreateParameter("@" + aFeild.FieldAttribute.FieldName
                , aFeild.FieldAttribute.DataType
                , (theFeildValue == null) ? DBNull.Value : theFeildValue);
        }
        /// <summary>
        /// 创建INSERT INTO语句
        /// </summary>
        /// <param name="obj"></param>
        /// <param name="theTableInfo"></param>
        /// <returns></returns>
        /// <exception cref="System.ArgumentException"></exception>
        /// <exception cref="System.ArgumentNullException"></exception>
        /// <exception cref="System.NotSupportedException"></exception>
        private DbCommand CreateInsertCommand(object obj, TableInfo theTableInfo)
        {
            // 检查表信息是否合法
            CheckTable(theTableInfo);

            List<DbParameter> theDbParams = new List<DbParameter>(theTableInfo.FieldList.Count + 1);
            StringBuilder builder = new StringBuilder("INSERT INTO ");
            builder.Append(theTableInfo.TableAttribute.TableName);
            builder.Append(" (");
            // 主键及其参数
            StringBuilder colList = new StringBuilder();
            StringBuilder valList = new StringBuilder();
            if (!theTableInfo.Primary.FieldAttribute.IsAutoIncrement)
            {
                colList.Append(theTableInfo.Primary.FieldAttribute.FieldName);
                valList.Append("@");
                valList.Append(theTableInfo.Primary.FieldAttribute.FieldName);
                theDbParams.Add(this.CreateDbParameter(obj, theTableInfo.Primary));
            }
            
            // INSERT子句非主键column列表和参数
            int i = 0;
            foreach (FieldInfo aFeild in theTableInfo.FieldList)
            {
                if (i == 0 && !theTableInfo.Primary.FieldAttribute.IsAutoIncrement)
                {
                    colList.Append(",");
                    valList.Append(",");
                }
                else if(i > 0)
                {
                    colList.Append(",");
                    valList.Append(",");
                }
                colList.Append(aFeild.FieldAttribute.FieldName);
                
                valList.Append("@");
                valList.Append(aFeild.FieldAttribute.FieldName);
                theDbParams.Add(CreateDbParameter(obj, aFeild));

                i++;
            }
            builder.Append(colList);
            builder.Append(") VALUES (");
            builder.Append(valList);
            builder.Append(")");
            if (theTableInfo.Primary.FieldAttribute.IsAutoIncrement)
            {
                builder.AppendLine();
                builder.Append(DbSession.GetLastAutoIncrementValueSql());
            }
            return DbSession.CreateCommand(builder.ToString(), theDbParams.ToArray());
        }
        /// <summary>
        /// 根据ORM对象类型创建基本的SELECT语句，如SELECT f1, f2 FROM table1
        /// </summary>
        /// <param name="theType"></param>
        /// <returns></returns>
        /// <exception cref="System.ArgumentNullException"></exception>
        /// <exception cref="System.ArgumentException"></exception>
        public static string CreateSelectSql(Type theType)
        {
            Checker.Assert<ArgumentNullException>(null != theType);

            TableInfo theTableInfo = CreateTableInfo(theType);

            CheckTable(theTableInfo);

            StringBuilder builder = new StringBuilder("SELECT ");
            CheckField(theTableInfo.Primary);
            builder.Append(theTableInfo.Primary.FieldAttribute.FieldName);
            foreach (FieldInfo aFeild in theTableInfo.FieldList)
            {
                builder.Append(",");
                // 检查字段信息是否合法
                CheckField(aFeild);
                builder.Append(aFeild.FieldAttribute.FieldName);
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
            object fieldValue = null;
            foreach (FieldInfo aField in theTableInfo.FieldList)
            {
                CheckField(aField);
                fieldValue = data[aField.FieldAttribute.FieldName];
                if(!(fieldValue is DBNull))
                {
                    aField.Property.SetValue(obj, fieldValue, null);
                }
            }
            CheckField(theTableInfo.Primary);
            theTableInfo.Primary.Property.SetValue(obj, data[theTableInfo.Primary.FieldAttribute.FieldName], null);
            return obj;
        }
        /// <summary>
        /// 以obj对应的属性值填充DbCommand的参数列表
        /// </summary>
        /// <param name="cmd">要填充参数值的DbCommand对象</param>
        /// <param name="obj">参数值来源对象</param>
        public static void FillDbParameters(DbCommand cmd, object obj)
        {
            Type theType = obj.GetType();
            TableInfo theTableInfo = CreateTableInfo(theType);
            // 检查表信息是否合法
            CheckTable(theTableInfo);
            string paramFieldName = String.Empty;
            foreach (DbParameter aParam in cmd.Parameters)
            {
                paramFieldName = aParam.ParameterName.Replace("@", String.Empty).Replace(":", String.Empty).Replace("$", String.Empty);
                if (paramFieldName.Equals(theTableInfo.Primary.FieldAttribute.FieldName))
                {
                    aParam.Value = theTableInfo.Primary.Property.GetValue(obj, null);
                }
                else
                {
                    foreach (FieldInfo aField in theTableInfo.FieldList)
                    {
                        if (paramFieldName.Equals(aField.FieldAttribute.FieldName))
                        {
                            aParam.Value = aField.Property.GetValue(obj, null);
                            break;
                        }
                    }
                }
            }
        }

        private class FieldInfo
        {
            public PropertyInfo Property;
            public FieldAttribute FieldAttribute;
        }
        private class TableInfo
        {
            public TableAttribute TableAttribute;
            public FieldInfo Primary;
            public List<FieldInfo> FieldList = new List<FieldInfo>();
        }
    }
}
