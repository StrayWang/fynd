using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Data.Common;
using FyndSharp.Utilities.Common;
using System.Reflection;

namespace FyndSharp.Data.Orm
{
    public class OrmObjectFactory
    {
        private static readonly Type TableAttributeType = typeof(TableAttribute);
        private static readonly Type FieldAttributeType = typeof(FieldAttribute);

        public AdoSession DbSession { get; set; }

        public DbCommand CreateSaveDbCommand(object obj)
        {
            Type theType = obj.GetType();
            object[] tableAttrs = theType.GetCustomAttributes(TableAttributeType, true);

            Checker.Assert<ArgumentException>(tableAttrs.Length > 0, "The class of 'obj' should contain FyndSharp.Data.Orm.TableAttribute.");

            TableInfo theTableInfo = new TableInfo();
            theTableInfo.TableAttribute = (TableAttribute)tableAttrs[0];

            PropertyInfo[] properties = theType.GetProperties();

            Checker.Assert<ArgumentException>(properties.Length > 0, "The class of 'obj' should contain one property at least.");

            FieldAttribute theCurrentFeild = null;
            object[] fieldAttrs = null;
            // TODO cache property infos
            foreach (PropertyInfo aProperty in properties)
            {
                fieldAttrs = aProperty.GetCustomAttributes(FieldAttributeType, true);
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

            object thePrimaryValue = theTableInfo.Primary.Property.GetValue(obj, null);
            if (null == thePrimaryValue || String.IsNullOrEmpty(thePrimaryValue.ToString()))
            {
                // TODO Insert
                StringBuilder builder = new StringBuilder("INSERT INTO ");
                builder.Append(theTableInfo.TableAttribute.TableName);
                builder.Append(" (");
                StringBuilder colList = new StringBuilder(theTableInfo.Primary.FeildAttribute.FieldName);
                StringBuilder valList = new StringBuilder("@");
                valList.Append(theTableInfo.Primary.FeildAttribute.FieldName);
                List<DbParameter> theDbParams = new List<DbParameter>(theTableInfo.FieldList.Count + 1);
                foreach (FieldInfo aFeild in theTableInfo.FieldList)
                {
                    colList.Append(",");
                    colList.Append(aFeild.FeildAttribute.FieldName);
                    valList.Append(",");
                    valList.Append("@");
                    valList.Append(aFeild.FeildAttribute.FieldName);
                    object theFeildValue = aFeild.Property.GetValue(obj, null);

                    Checker.Assert<ArgumentException>(aFeild.FeildAttribute.AllowNull || (!aFeild.FeildAttribute.AllowNull && null != theFeildValue)
                        , String.Format("The field {0} can not be null.", aFeild.FeildAttribute.FieldName));
                    if (null == theFeildValue)
                    {
                        theFeildValue = DBNull.Value;
                    }
                    theDbParams.Add(this.DbSession.CreateParameter("@" + aFeild.FeildAttribute.FieldName, aFeild.FeildAttribute.DataType, theFeildValue));
                }
                builder.Append(colList);
                builder.Append(") VALUES (");
                builder.Append(valList);
                builder.Append(")");

                return DbSession.CreateCommand(builder.ToString(), theDbParams.ToArray());
            }
            else
            {
                // TODO Update
                return null;
            }
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
