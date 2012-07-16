using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using FyndSharp.Utilities.Common;

namespace FyndSharp.Data.Orm
{
    [AttributeUsage(AttributeTargets.Class | AttributeTargets.Struct, Inherited = true)]
    public class TableAttribute : Attribute
    {
        public string TableName { get; set; }

        public string SelectionPostfix { get; set; }

        public string UpdationPostfix { get; set; }

        public string DeletionPostfix { get; set; }

        public TableAttribute(string theTableName)
        {
            Checker.Assert<ArgumentNullException>(!String.IsNullOrEmpty(theTableName));
            this.TableName = theTableName;
        }
    }
}
