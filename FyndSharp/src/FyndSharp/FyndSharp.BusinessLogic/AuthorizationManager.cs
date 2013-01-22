using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FyndSharp.BusinessLogic
{
    public class AuthorizationManager
    {
        public IList<string> ExcludedPaths { get; private set; }

        public bool Authorize(Uri anUri)
        {
            return false;
        }

        private static bool ExistsInExcludedPaths(IList<string> paths, string target)
        {
            if (String.IsNullOrEmpty(target))
            {
                return false;
            }
            if(paths == null || paths.Count <= 0)
            {
                return false;
            }
            foreach (string aPath in paths)
            {
                if (target.IndexOf(aPath, StringComparison.OrdinalIgnoreCase) > -1)
                {
                    return true;
                }
            }
            return false;
        }
    }

    
}
