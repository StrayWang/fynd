using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Runtime.Serialization;
using System.Runtime.Serialization.Formatters.Binary;
using System.IO;
using System.Security.Cryptography;
using System.Text.RegularExpressions;
using System.Collections;

namespace FyndSharp.Utilities.Common
{
    /// <summary>
    /// 类型或内容转换工具
    /// </summary>
    public static class TypeConvert
    {
        /// <summary>
        /// 将对象转换成String类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <returns>obj对应的String形式，无法转换时返回String.Empty</returns>
        public static string ToString(object obj)
        {
            return ToString(obj, String.Empty);
        }
        /// <summary>
        /// 将对象转换成String类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <param name="defaultString">无法转换时返回的默认值</param>
        /// <returns>obj对应的String形式，无法转换时返回defaultString参数指定的值</returns>
        public static string ToString(object obj, string defaultString)
        {
            if (null == obj)
            {
                return defaultString;
            }
            return obj.ToString();
        }
        /// <summary>
        /// 将对象转换成Int32类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <returns>obj对应的Int32形式，无法转换时返回0</returns>
        public static int ToInt32(object obj)
        {
            return ToInt32(obj, 0);
        }
        /// <summary>
        /// 将对象转换成Int32类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <param name="defaultValue">无法转换时返回的默认值</param>
        /// <returns>obj对应的Int32形式，无法转换时返回defaultValue参数指定的值</returns>
        public static int ToInt32(object obj, int defaultValue)
        {
            if (null == obj)
            {
                return defaultValue;
            }
            try
            {
                return Convert.ToInt32(obj);
            }
            catch
            {
                return defaultValue;
            }
        }
        /// <summary>
        /// 将对象转换成Float类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <returns>obj对应的Float形式，无法转换时返回0.0F</returns>
        public static float ToFloat(object obj)
        {
            return ToFloat(obj, 0.0F);
        }
        /// <summary>
        /// 将对象转换成Float类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <param name="defaultValue">无法转换时返回的默认值</param>
        /// <returns>obj对应的Float形式，无法转换时返回defaultValue参数指定的值</returns>
        public static float ToFloat(object obj, float defaultValue)
        {
            if (null == obj)
            {
                return defaultValue;
            }
            try
            {
                return Convert.ToSingle(obj);
            }
            catch
            {
                return defaultValue;
            }
        }

        /// <summary>
        /// 将对象转换成Double类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <returns>obj对应的Double形式，无法转换时返回0.0F</returns>
        public static double ToDouble(object obj)
        {
            return ToDouble(obj, 0.0D);
        }
        /// <summary>
        /// 将对象转换成Double类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <param name="defaultValue">无法转换时返回的默认值</param>
        /// <returns>obj对应的Double形式，无法转换时返回defaultValue参数指定的值</returns>
        public static double ToDouble(object obj, double defaultValue)
        {
            if (null == obj)
            {
                return defaultValue;
            }
            try
            {
                return Convert.ToDouble(obj);
            }
            catch
            {
                return defaultValue;
            }
        }


        /// <summary>
        /// 将对象转换成Boolean类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <returns>obj对应的Boolean形式，无法转换时返回False</returns>
        public static bool ToBool(object obj)
        {
            return ToBool(obj, false);
        }
        /// <summary>
        /// 将对象转换成Boolean类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <param name="defaultValue">无法转换时返回的默认值</param>
        /// <returns>obj对应的Boolean形式，无法转换时返回defaultValue参数指定的值</returns>
        public static bool ToBool(object obj, bool defaultValue)
        {
            if (null == obj)
            {
                return defaultValue;
            }
            if (ToInt32(obj) == 1)
            {
                return true;
            }
            try
            {
                return Convert.ToBoolean(obj);
            }
            catch
            {
                return defaultValue;
            }
        }
        /// <summary>
        /// 通过序列化和反序列化复制对象
        /// </summary>
        /// <typeparam name="T">要复制的对象的类型</typeparam>
        /// <param name="source">要复制的对象</param>
        /// <returns></returns>
        public static T CopyBySerialize<T>(T source)
        {
            if (!typeof(T).IsSerializable)
            {
                throw new System.ArgumentException("The type must be serializeble.", "source");
            }
            if (Object.ReferenceEquals(source, null))
            {
                return default(T);
            }
            IFormatter formatter = new BinaryFormatter();
            using (Stream theStream = new MemoryStream())
            {
                formatter.Serialize(theStream, source);
                theStream.Seek(0, SeekOrigin.Begin);
                return (T)formatter.Deserialize(theStream);
            }
        }
        /// <summary>
        /// 将对象转换成Decimal类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <returns>obj对应的Decimal形式，无法转换时返回0.0M</returns>
        public static decimal ToDecimal(object obj)
        {
            return ToDecimal(obj, 0.0M);
        }
        /// <summary>
        /// 将对象转换成Decimal类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <param name="defaultValue">无法转换时返回的默认值</param>
        /// <returns>obj对应的Decimal形式，无法转换时返回defaultValue参数指定的值</returns>
        public static decimal ToDecimal(object obj, decimal defaultValue)
        {
            if (null == obj)
            {
                return defaultValue;
            }
            try
            {
                return Convert.ToDecimal(obj);
            }
            catch
            {
                return defaultValue;
            }
        }
        /// <summary>
        /// 将对象转换成DateTime类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <returns>obj对应的DateTime形式，无法转换时返回DateTime的默认实例</returns>
        public static DateTime ToDateTime(object obj)
        {
            return ToDateTime(obj, new DateTime());
        }
        /// <summary>
        /// 将对象转换成DateTime类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <param name="defaultValue">无法转换时返回的默认值</param>
        /// <returns>obj对应的DateTime形式，无法转换时返回defaultValue参数指定的值</returns>
        public static DateTime ToDateTime(object obj, DateTime defaultValue)
        {
            if (null == obj)
            {
                return defaultValue;
            }
            try
            {
                return Convert.ToDateTime(obj);
            }
            catch
            {
                return defaultValue;
            }
        }
        /// <summary>
        /// 将对象转换成Int64类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <returns>obj对应的Int64形式，无法转换时返回0L</returns>
        public static Int64 ToInt64(object obj)
        {
            return ToInt64(obj, 0L);
        }
        /// <summary>
        /// 将对象转换成Int64类型
        /// </summary>
        /// <param name="obj">要转换的对象</param>
        /// <param name="defaultValue">无法转换时返回的默认值</param>
        /// <returns>obj对应的Int64形式，无法转换时返回defaultValue参数指定的值</returns>
        public static Int64 ToInt64(object obj, Int64 defaultValue)
        {
            if (null == obj)
            {
                return defaultValue;
            }
            try
            {
                return Convert.ToInt64(obj);
            }
            catch
            {
                return defaultValue;
            }
        }
        /// <summary>
        /// 按汇率进行货币转换
        /// </summary>
        /// <param name="srcCurrencyCode">原币代码，大写</param>
        /// <param name="srcExchangeRate">原币汇率</param>
        /// <param name="dstCurrencyCode"></param>
        /// <param name="dstExchangeRate"></param>
        /// <param name="srcAmount">原币金额</param>
        /// <returns></returns>
        public static decimal ToCurrency(string srcCurrencyCode, decimal srcExchangeRate
            , string dstCurrencyCode, decimal dstExchangeRate, decimal srcAmount)
        {
            decimal dstMoney = srcAmount;

            if (srcCurrencyCode != "USD")
            {
                if (srcExchangeRate > 0)
                {
                    dstMoney = dstMoney / srcExchangeRate;
                }
            }

            if (dstCurrencyCode != "USD")
            {
                if (dstExchangeRate > 0)
                {
                    dstMoney = dstMoney * dstExchangeRate;
                }
            }
            return dstMoney;
        }
        /// <summary>
        /// 判断obj是否等于null，是则返回DBNull.Value,否则返回obj本身
        /// </summary>
        /// <param name="obj"></param>
        /// <returns></returns>
        public static object ToDbValue(object obj)
        {
            if (null == obj)
            {
                return DBNull.Value;
            }
            return obj;
        }
        /// <summary>
        /// 按取模哈希算法得到对象的哈希值
        /// </summary>
        /// <param name="obj">要计算哈希值的对象</param>
        /// <param name="seed">取模基数</param>
        /// <returns>如果obj == null，则返回0，如果seed == 0，则返回0，除此之外返回Math.Abs(obj.GetHashCode % seed)</returns>
        public static int ToModHashValue(object obj, int seed)
        {
            if (null == obj)
            {
                return 0;
            }
            if (seed == 0)
            {
                return 0;
            }
            int hash = obj.GetHashCode();
            return Math.Abs(hash % seed);

        }
        /// <summary>
        /// 计算字符串MD5值，并将MD5值转换成对应的字符串形式
        /// </summary>
        /// <param name="aString">要计算MD5值</param>
        /// <param name="anEncoding">MD5值字符串编码</param>
        /// <returns></returns>
        public static string ToMD5String(string aString, Encoding anEncoding)
        {
            MD5 m = new MD5CryptoServiceProvider();
            byte[] s = m.ComputeHash(anEncoding.GetBytes(aString));
            return BitConverter.ToString(s).Replace("-", "");
        }
        /// <summary>
        /// 将整数转换成指定的枚举类型
        /// </summary>
        /// <typeparam name="T">要转换为的枚举类型</typeparam>
        /// <param name="value"></param>
        /// <returns></returns>
        public static T ToEnum<T>(int value)
        {
            return (T)Enum.ToObject(typeof(T), value);
        }

        /// <summary>
        /// 尝试将对象转换JSON格式的扩展方法
        /// </summary>
        /// <param name="anObject"></param>
        /// <returns></returns>
        /// <exception cref="System.InvalidOperationException">所生成的 JSON 字符串超出了 System.Web.Script.Serialization.JavaScriptSerializer.MaxJsonLength 的值。- 或 - obj 包含循环引用。当子对象引用父对象，而父对象又引用子对象时，将会发生循环引用。</exception>
        /// <exception cref="System.ArgumentException">超出了由 System.Web.Script.Serialization.JavaScriptSerializer.RecursionLimit 定义的递归限制。</exception>
        public static string ToJson(this object anObject)
        {
            System.Web.Script.Serialization.JavaScriptSerializer serializer = new System.Web.Script.Serialization.JavaScriptSerializer();
            return serializer.Serialize(anObject);
        }
        /// <summary>
        /// 将HTML转换为纯文本，即去掉所有HTML标记。
        /// </summary>
        /// <param name="html"></param>
        /// <returns></returns>
        public static string ToText(string html)
        {
            return Regex.Replace(html, "</?[a-z][a-z0-9]*[^<>]*>", String.Empty, RegexOptions.IgnoreCase);
        }
        /// <summary>
        /// 将字符串集合转换成半角逗号分隔的，用于SQL语句中IN子句的字符串
        /// </summary>
        /// <param name="list"></param>
        /// <returns></returns>
        public static string ToSqlInClause(IEnumerable<string> list)
        {
            return ToSequenceString(list, "'");
        }
        /// <summary>
        /// 将long集合转换成半角逗号分隔的，用于SQL语句中IN子句的字符串
        /// </summary>
        /// <param name="list"></param>
        /// <returns></returns>
        public static string ToSqlInClause(IEnumerable<long> list)
        {
            return ToSequenceString(list, String.Empty);
        }
        /// <summary>
        /// 将int集合转换成半角逗号分隔的，用于SQL语句中IN子句的字符串
        /// </summary>
        /// <param name="list"></param>
        /// <returns></returns>
        public static string ToSqlInClause(IEnumerable<int> list)
        {
            return ToSequenceString(list, String.Empty);
        }
        /// <summary>
        /// 将对象集合转换成半角逗号分隔的，用于SQL语句中IN子句的字符串
        /// </summary>
        /// <param name="collection">对象集合</param>
        /// <param name="wrapper">用该参数对应的字符串将对象的字符串形式包裹起来</param>
        /// <returns></returns>
        public static string ToSequenceString(IEnumerable collection, string wrapper)
        {
            return ToSequenceString(collection, wrapper, delegate(object anObject) { return TypeConvert.ToString(anObject); });
        }
        /// <summary>
        /// 将对象集合转换成半角逗号分隔的，用于SQL语句中IN子句的字符串
        /// </summary>
        /// <param name="collection">对象集合</param>
        /// <param name="wrapper">用该参数对应的字符串将对象的字符串形式包裹起来</param>
        /// <param name="theToStringFunc">对象转换成字符串的代理函数，在需要将对象转换成字符串时会使用该代理方法的返回值替换默认对象字符串值</param>
        /// <returns></returns>
        public static string ToSequenceString(IEnumerable collection, string wrapper, ToStringFunction theToStringFunc)
        {
            string sqlInClause = String.Empty;
            foreach (object anObject in collection)
            {
                sqlInClause += wrapper + theToStringFunc(anObject) + wrapper + ",";
            }
            if (sqlInClause.EndsWith(","))
            {
                sqlInClause = sqlInClause.Remove(sqlInClause.Length - 1, 1);
            }
            return sqlInClause;
        }
        /// <summary>
        /// 用于ToSequenceString(IEnumerable, string, ToStringFunction)方法转换对象为字符串
        /// </summary>
        /// <param name="anObject"></param>
        /// <returns></returns>
        public delegate string ToStringFunction(object anObject);
    }
}
