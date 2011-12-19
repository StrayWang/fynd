using System.IO;

namespace FyndSharp.Utilities.Serialization
{
    public interface ISerializer<T>
    {
        T Read(Stream stream);
        void Write(T value, Stream stream);
    }
}
