using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Data;
using FyndSharp.Utilities.Common;

namespace FyndSharp.Data.Orm
{
    [AttributeUsage(AttributeTargets.Property, Inherited = true)]
    public class FieldAttribute : Attribute
    {
        public string FieldName { get; set; }
        public DbType DataType { get; set; }
        public bool AllowNull { get; set; }
        public int DataSize { get; set; }
        public bool IsPrimary { get; set; }
        public Type ParentType { get; set; }

        public FieldAttribute(string theFieldName, DbType theDataType)
            : this(theFieldName, theDataType, 0, true, false, null)
        {

        }

        public FieldAttribute(string theFieldName, DbType theDataType, bool allowNullFlag)
            : this(theFieldName, theDataType, 0, allowNullFlag, false, null)
        {

        }

        public FieldAttribute(string theFieldName, DbType theDataType, int theDataSize, bool allowNullFlag, bool isPrimaryFlag, Type theParentType)
        {
            Checker.Assert<ArgumentNullException>(!String.IsNullOrEmpty(theFieldName));
            Checker.Assert<ArgumentOutOfRangeException>(theDataSize >= 0);

            this.FieldName = theFieldName;
            this.DataSize = theDataSize;
            this.DataType = theDataType;
            this.AllowNull = allowNullFlag;
            this.IsPrimary = isPrimaryFlag;
            this.ParentType = theParentType;

            if (this.IsPrimary)
            {
                this.AllowNull = false;
            }
        }

    }
}
