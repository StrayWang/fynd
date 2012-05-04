using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Data;

namespace FyndSharp.Data
{
    /// <summary>
    /// FieldDifferent类表示数据库表中字段差异
    /// </summary>
    public class FieldDifferent
    {
        /// <summary>
        /// 获取或设置字段名称
        /// </summary>
        public string FieldName { get; set; }
        /// <summary>
        /// 获取或设置字段数据类型
        /// </summary>
        public DbType DataType { get; set; }
        /// <summary>
        /// 获取或设置字段新值
        /// </summary>
        public object NewValue { get; set; }
        /// <summary>
        /// 获取或设置字段新值对应的其他字段
        /// </summary>
        public DatabaseField NewValueField { get; set; }
        /// <summary>
        /// 获取或设置一个值，表示该字段是否使用父表的主键值作为它的值
        /// </summary>
        public bool UseParentPrimaryFieldValue { get; set; }

        public FieldDifferent()
        {

        }

        public FieldDifferent(string name, DbType dataType, object newValue)
        {
            FieldName = name;
            DataType = dataType;
            NewValue = newValue;
        }
        public FieldDifferent(string name, DbType dataType, DatabaseField aNewValueField)
        {
            FieldName = name;
            DataType = dataType;
            NewValue = null;
            NewValueField = aNewValueField;
        }

    }

    public class DatabaseField
    {
        public string Name { get; set; }
        public DbType DataType { get; set; }
        public object Value { get; set; }
        public bool IsPrimaryField { get; set; }

        public DatabaseField() { }
        public DatabaseField(string name, DbType dataType)
            : this(name, dataType, null)
        {

        }
        public DatabaseField(string name, DbType dataType, object value)
        {
            Name = name;
            DataType = dataType;
            Value = value;
        }
    }

    public class RowDifferent
    {
        public List<DatabaseField> PrimaryKeys { get; set; }
        public string TableName { get; set; }
        public DataRowState RowState { get; set; }
        public List<FieldDifferent> FieldDifferentList { get; set; }

        public List<RowDifferent> Children { get; set; }

        public RowDifferent()
        {
            PrimaryKeys = new List<DatabaseField>();
            FieldDifferentList = new List<FieldDifferent>();
            Children = new List<RowDifferent>();
        }
    }
}
