using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Runtime.Serialization;
using System.Runtime.Serialization.Formatters.Binary;
using System.IO;
using System.Security.Cryptography;

namespace FyndSharp.Utilities.Common
{
    public static class TypeConvert
    {
        public static string ToString(object obj)
        {
            return ToString(obj, String.Empty);
        }
        public static string ToString(object obj, string defaultString)
        {
            if (null == obj)
            {
                return defaultString;
            }
            return obj.ToString();
        }

        public static int ToInt32(object obj)
        {
            return ToInt32(obj, 0);
        }
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

        public static float ToFloat(object obj)
        {
            return ToFloat(obj, 0.0F);
        }
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

        public static bool ToBool(object obj)
        {
            return ToBool(obj, false);
        }
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
        public static decimal ToDecimal(object obj)
        {
            return ToDecimal(obj, 0.0M);
        }
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

        public static DateTime ToDateTime(object obj)
        {
            return ToDateTime(obj, new DateTime());
        }
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
        public static Int64 ToInt64(object obj)
        {
            return ToInt64(obj, 0L);
        }
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

        public static object ToDbValue(object obj)
        {
            if (null == obj)
            {
                return DBNull.Value;
            }
            return obj;
        }

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

        public static string ToMD5String(string aString, Encoding anEncoding)
        {
            MD5 m = new MD5CryptoServiceProvider();
            byte[] s = m.ComputeHash(anEncoding.GetBytes(aString));
            return BitConverter.ToString(s).Replace("-", "");
        }

        public static T ToEnum<T>(int value)
        {
            return (T)Enum.ToObject(typeof(T), value);
        }
    }
}
