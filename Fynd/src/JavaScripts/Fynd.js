var Fynd = {};

Fynd.UI = {};

Fynd.UI.ComponentStack = {
    _cmps : {},    
    register : function(id,cmp)
    {
        if(this._cmps[id])
        {
            return;
        }
        this._cmps[id] = cmp;
    },
    find : function(id)
    {
        if(this._cmps[id])
        {
            return this._cmps[id];
        }
        return document.getElementById(id);
    },
    remove : function(id)
    {
        delete this._cmps[id]; 
    }
}