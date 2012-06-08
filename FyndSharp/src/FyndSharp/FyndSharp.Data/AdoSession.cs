using System;
using System.Data;
using System.Data.SqlClient;
using System.Collections.Generic;
using System.Text;
using System.Data.Common;


namespace FyndSharp.Data
{
    public abstract class AdoSession : IDisposable
    {
        protected string ConnectionString = String.Empty;
        protected DbTransaction Transaction;
        protected DbConnection Connection;

        public bool IsDisposed { get; protected set; }

        #region 构造与析构
        public AdoSession(string theConnectionString)
        {
            this.ConnectionString = theConnectionString;
            this.Connection = this.CreateDbConnection();
            this.Connection.ConnectionString = theConnectionString;
        }
        ~AdoSession()
        {
            Dispose(false);
        }
        #endregion

        /// <summary>
        /// 建立数据库连接
        /// </summary>
        /// <returns>如果之前未建立连接，则返回True，反之返回False</returns>
        /// <remarks>
        /// 如果返回True，则调用方负责调用Close关闭该连接。
        /// 如果返回False，调用方不能关闭该连接，因为可能该连接还在被其他对象使用。
        /// 遵循谁打开谁关闭的原则。
        /// </remarks>
        public virtual bool Open()
        {
            if (Connection.State == ConnectionState.Open)
            {
                return false;
            }
            Connection.Open();
            return true;
        }
        public virtual void Close()
        {
            if (Connection.State != ConnectionState.Closed)
            {
                Connection.Close();
            }
        }
        public virtual bool BeginTransaction()
        {
            if (null == Transaction)
            {
                Transaction = Connection.BeginTransaction();
                return true;
            }
            return false;
        }
        public virtual void Commit()
        {
            if (null != Transaction)
            {
                Transaction.Commit();
                Transaction = null;
            }
        }
        public virtual void Rollback()
        {
            if (null != Transaction)
            {
                Transaction.Rollback();
                Transaction = null;
            }
        }

        public virtual void ApplyChanges(List<RowDifferent> rowDiffList)
        {
            List<DbParameter> parameters = new List<DbParameter>();
            string sql = String.Empty;
            bool isConnOpen = Open();
            bool isTransBegin = BeginTransaction();
            try
            {
                foreach (RowDifferent rowDiff in rowDiffList)
                {
                    if (rowDiff.RowState == DataRowState.Added)
                    {
                        sql = CreateInsertSql(rowDiff, parameters);
                    }
                    else if (rowDiff.RowState == DataRowState.Deleted)
                    {
                        sql = CreateDeleteSql(rowDiff, parameters);
                    }
                    else if (rowDiff.RowState == DataRowState.Modified)
                    {
                        sql = CreateUpdateSql(rowDiff, parameters);
                    }
                    if (!String.IsNullOrEmpty(sql))
                    {
                        using (DbCommand cmd = this.CreateCommand(sql, parameters.ToArray()))
                        {
                            this.ExecuteNonQuery(cmd);
                        }
                    }
                    sql = String.Empty;
                    parameters.Clear();
                }
                if (isTransBegin)
                {
                    Commit();
                }
            }
            catch
            {
                if (isTransBegin)
                {
                    Rollback();
                }
                throw;
            }
            finally
            {
                if (isConnOpen)
                {
                    Close();
                }
            }
        }
        private string CreateInsertSql(RowDifferent rowDiff, List<DbParameter> parameters)
        {
            if (rowDiff.RowState != DataRowState.Added)
            {
                return String.Empty;
            }
            StringBuilder builder = new StringBuilder("INSERT INTO");
            builder.AppendLine(rowDiff.TableName);

            StringBuilder fieldsBuilder = new StringBuilder();
            StringBuilder valuesBuilder = new StringBuilder();
            foreach (FieldDifferent field in rowDiff.FieldDifferentList)
            {
                fieldsBuilder.Append(field.FieldName);
                fieldsBuilder.Append(",");
                valuesBuilder.Append("@");
                valuesBuilder.Append(field.FieldName);
                valuesBuilder.Append(",");
                parameters.Add(CreateParameter("@" + field.FieldName, field.DataType, field.NewValue));
            }
            if (rowDiff.FieldDifferentList.Count > 0)
            {
                fieldsBuilder = fieldsBuilder.Remove(fieldsBuilder.Length - 1, 1);
                valuesBuilder = valuesBuilder.Remove(valuesBuilder.Length - 1, 1);
            }
            builder.AppendLine("(");
            builder.AppendLine(fieldsBuilder.ToString());
            builder.AppendLine(") VALUES (");
            builder.AppendLine(valuesBuilder.ToString());
            builder.AppendLine(");");

            return builder.ToString();
        }
        private string CreateUpdateSql(RowDifferent rowDiff, List<DbParameter> parameters)
        {
            if (rowDiff.RowState != DataRowState.Modified || rowDiff.FieldDifferentList.Count <= 0 || rowDiff.PrimaryKeys.Count <= 0)
            {
                return String.Empty;
            }
            StringBuilder builder = new StringBuilder("UPDATE ");
            builder.Append(rowDiff.TableName);
            builder.Append(" WITH(ROWLOCK) ");
            builder.Append(" SET ");
            foreach (FieldDifferent field in rowDiff.FieldDifferentList)
            {
                builder.Append(field.FieldName);
                if (field.NewValueField != null)
                {
                    //将同一个表的一个字段更新到另外一个字段中
                    builder.Append("=");
                    builder.Append(field.NewValueField.Name);
                }
                else
                {
                    builder.Append("=@");
                    builder.Append(field.FieldName);
                    parameters.Add(CreateParameter("@" + field.FieldName, field.DataType, field.NewValue));
                }
                builder.Append(",");
            }
            if (rowDiff.FieldDifferentList.Count > 0)
            {
                builder = builder.Remove(builder.Length - 1, 1);
            }
            builder.Append(" WHERE ");
            foreach (DatabaseField primary in rowDiff.PrimaryKeys)
            {
                builder.Append(primary.Name);
                builder.Append("=@");
                builder.Append(primary.Name);
                builder.Append(" AND ");

                parameters.Add(CreateParameter("@" + primary.Name, primary.DataType, primary.Value));
            }
            builder = builder.Remove(builder.Length - 5, 5);
            builder.Append(";");

            return builder.ToString();
        }
        private string CreateDeleteSql(RowDifferent rowDiff, List<DbParameter> parameters)
        {
            if (rowDiff.RowState != DataRowState.Deleted || (rowDiff.FieldDifferentList.Count <= 0 && rowDiff.PrimaryKeys.Count <= 0))
            {
                return String.Empty;
            }
            StringBuilder builder = new StringBuilder("DELETE FROM");
            builder.AppendLine(rowDiff.TableName);
            builder.Append("WHERE ");

            foreach (FieldDifferent field in rowDiff.FieldDifferentList)
            {
                builder.Append(field.FieldName);
                builder.Append("=@");
                builder.AppendLine(field.FieldName);
                builder.Append("AND ");
                parameters.Add(CreateParameter("@" + field.FieldName, field.DataType, field.NewValue));
            }
            if (rowDiff.FieldDifferentList.Count > 0)
            {
                builder = builder.Remove(builder.Length - 4, 4);
            }
            builder.AppendLine("WHERE");
            foreach (DatabaseField primary in rowDiff.PrimaryKeys)
            {
                builder.Append(primary.Name);
                builder.Append("=@");
                builder.AppendLine(primary.Name);
                builder.Append("AND ");
                parameters.Add(CreateParameter("@" + primary.Name, primary.DataType, primary.Value));
            }
            if (rowDiff.PrimaryKeys.Count > 0)
            {
                builder = builder.Remove(builder.Length - 4, 4);
            }
            builder.Append(";");
            return builder.ToString();
        }

        public virtual DbCommand CreateCommand(string sql)
        {
            DbCommand cmd = CreateDbCommand();
            cmd.CommandText = sql;
            return cmd;
        }
        public virtual DbCommand CreateCommand()
        {
            return CreateCommand(String.Empty);
        }
        public virtual DbCommand CreateCommand(string sql, DbParameter[] parameters)
        {
            DbCommand cmd = CreateCommand(sql);
            cmd.Parameters.AddRange(parameters);
            return cmd;
        }

        public virtual int ExecuteNonQuery(DbCommand cmd)
        {
            bool isOpen = this.Open();
            try
            {
                cmd.Connection = Connection;
                if (null != Transaction)
                {
                    cmd.Transaction = Transaction;
                }
                return cmd.ExecuteNonQuery();
            }
            finally
            {
                if (isOpen)
                {
                    this.Close();
                }
            }
        }
        /// <summary>
        /// 执行SQL命令，返回SqlDataReader，使用该方法必须自己管理数据库连接。
        /// </summary>
        /// <param name="cmd"></param>
        /// <returns></returns>
        public virtual IDataReader ExecuteReader(DbCommand cmd)
        {
            bool isOpen = this.Open();
            cmd.Connection = Connection;
            if (null != Transaction)
            {
                cmd.Transaction = Transaction;
            }
            return cmd.ExecuteReader();
        }

        public virtual object ExecuteScalar(DbCommand cmd)
        {
            bool isOpen = this.Open();
            try
            {
                cmd.Connection = Connection;
                if (null != Transaction)
                {
                    cmd.Transaction = Transaction;
                }
                return cmd.ExecuteScalar();
            }
            finally
            {
                if (isOpen)
                {
                    this.Close();
                }
            }
        }

        public virtual DataTable ExecuteDataTable(DbCommand cmd)
        {
            return ExecuteDataSet(cmd).Tables[0];
        }
        public virtual DataSet ExecuteDataSet(DbCommand cmd)
        {
            bool isOpen = this.Open();
            try
            {
                cmd.Connection = Connection;
                if (null != Transaction)
                {
                    cmd.Transaction = Transaction;
                }
                IDataAdapter adapter = CreateDataAdapter(cmd);
                DataSet aDataSet = new DataSet();
                adapter.Fill(aDataSet);
                return aDataSet;
            }
            finally
            {
                if (isOpen)
                {
                    this.Close();
                }
            }
        }
        /// <summary>
        /// 创建SQL Server查询参数
        /// </summary>
        /// <param name="name">参数名</param>
        /// <param name="dataType">参数数据类型</param>
        /// <param name="value">参数值</param>
        /// <returns></returns>
        public DbParameter CreateParameter(string name, DbType dataType, object value)
        {
            DbParameter theParameter = this.CreateDbParameter();
            theParameter.ParameterName = name;
            theParameter.DbType = dataType;
            theParameter.Value = value;
            return theParameter;
        }
        #region IDisposable 成员

        public void Dispose()
        {
            if (!IsDisposed)
            {
                Dispose(true);
                IsDisposed = true;
            }
            GC.SuppressFinalize(this);
        }

        #endregion
        protected virtual void Dispose(bool disposing)
        {
            if (disposing)
            {
                if (null != Transaction)
                {
                    Transaction.Dispose();
                }
                if (null != Connection)
                {
                    Connection.Dispose();
                }
            }
        }

        protected abstract DbConnection CreateDbConnection();
        protected abstract DbCommand CreateDbCommand();
        protected abstract DbParameter CreateDbParameter();
        protected abstract IDataAdapter CreateDataAdapter(DbCommand cmd);
        public abstract string GetLastAutoIncrementValueSql();
    }
}