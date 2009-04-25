Fynd.UI.Picker = function(id,config)
{
    this.selectedId = (config.selectedId) ? selectedId : [];
    this.tableId = config.tableId;
    this.tableIdKey = config.tableIdKey;
    this.tableLabelKey = config.tableLabelKey;
    this.multiSelection = config.multiSelection;
    this.href = config.href;
    this.id = id;
    this.yuiDialog = null;
    this.dialogTable = null;
    this.dialogTree = null;
    this.dialogTableDataSource = null;
    this.tableElId = this.id + '-table';
    this.treeElId = this.id + '-tree';
    
    this.selectedTreeNodeDef = null;
};
Fynd.UI.Picker.prototype = 
{
    initSelectedId : function()
    {
        var tableObj = Fynd.UI.ComponentStack.find(this.tableId);
        if(tableObj)
        {
            var rs = tableObj.getRecordSet();
            for(var i = 0;i<rs.getLength();i++)
            {
                var record = rs.getRecord(i);
                this.selectedId[i][this.tableIdKey] = record.getData(this.tableIdKey);
                this.selectedId[i][this.tableLabelKey] = record.getData(this.tableLabelKey);
            }
        }
    },
    init : function()
    {
        this.initSelectedId();
        
        var pickerEl = document.getElementId(this.id);
        var divTable = document.createElement("div");
        pickerEl.appendChild(divTable);
        divTable.id = this.tableElId;
        //TODO:build search bar
        
        var divTree = document.createElement('div');
        pickerEl.appendChild(divTree);
        divTree.id = this.treeElId;
        
        var divButton = document.createElement('div');
        pickerEl.appendChild(divButton);
        divButton.id = this.id + '-button';
        
        var successHandler = function(p)
        {
            var responseObj = YAHOO.lang.JSON.parse(p.responseText);
            
            //begin table creation
            //create table column definition
            var dialogTableColumDef = responseObj.table.columnDefinition;
            var selectionFormartter = function(elCell, oRecord, oColumn, oData)
            {
                var checked = false;
                for(var i=0;i<existId.length;i++)
                {
                    if(oData == existId[i])
                    {
                        checked = true;
                        break;
                    }
                }
                var inputType = (this.multiSelection) ? 'checkbox' : 'radio';
                elCell.innerHTML = '<input type="' + inputType + '"' + (checked ? ' checked' : '') +
                    ' name="' + oColumn.getKey() + '-' + inputType + '" />';
            };
            dialogTableColumDef[0].formatter = selectionFormartter;
            
            //create table configures.
            var dialogTableConfig = {
                initialRequest  : "&fetch=table&startIndex=0&results=10",
                dynamicData     : true,
                paginator       : new YAHOO.widget.Paginator({ rowsPerPage:10 }),
                generateRequest : function(oState, oSelf){
                    oState = oState || { pagination:null, sortedBy:null };
                    var sort = (oState.sortedBy) ? oState.sortedBy.key : '';
                    var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_DESC) ? "desc" : "asc";
                    var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
                    var results = (oState.pagination) ? oState.pagination.rowsPerPage : null;
                    return  "fetch=table&" + 
                        ((this.selectedTreeNodeDef) ? 'query=' + this.selectedTreeNodeDef.value+'&' : '') +
                        ((sort == '') ? '' : "sort=" + sort + "&dir=" + dir + "&") +
                            "startIndex=" + startIndex +
                            ((results !== null) ? "&results=" + results : "");
                }
            };
            //create table data source.
            this.dialogTableDataSource = new YAHOO.util.DataSource(this.href + "fetch=table&");
            this.dialogTableDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
            this.dialogTableDataSource.responseSchema = {
                resultsList : "records",
                metaFields  : {
                    totalRecords : "totalRecords"
                }
            };
            this.dialogTable = new YAHOO.widget.DataTable(this.id, dialogTableColumDef, this.dialogTableDataSource, dialogTableConfig);
            
            //subscribe the checkbox or radio's click event to add the current selected value to array of selected value .
            if(this.multiSelection)
            {
                this.dialogTable.subscribe('checkboxClickEvent', function(e)
                {
                    var checked = e.target.checked;
                    var col = this.getColumn(e.target);
                    var record = this.getRecord(e.target);
                    var value = record.getData(col.key);
                    var existent = false;
                    if(checked)
                    {
                        for(var i=0;i<this.selectedId.length;i++)
                        {
                            if(value == this.selectedId[i][this.tabelIdKey])
                            {
                                existent = true;
                                break;
                            }
                        }
                        if(!existent)
                        {
                            this.selectedId.push(value);
                        }
                    }
                };
            }
            else
            {
                this.dialogTable.subscribe('radioClickEvent', function(e)
                {
                    var checked = e.target.checked;
                    var col = this.getColumn(e.target);
                    var record = this.getRecord(e.target);
                    var value = record.getData(col.key);
                    var existent = false;
                    if(checked)
                    {
                        this.selectedId[0] = value;
                    }
                };
            }
            //end table creation
            
            var dialogTreeConfig = responseObj.tree.config;
            var dialogTreeData = responseObj.tree.data;
            
            this.dialogTree = new YAHOO.widget.TreeView(this.treeElId);
            this.initTreeNode(this.dialogTree.getRoot(),dialogTreeData);
            
            var treeNodeClickHandler = function(e,node)
            {
                var nodeDef = node.getNodeDefinition();
                var url = 'fetch=table&startIndex=0&results=10&query=' + nodeDef.value;
                this.dialogTableDataSource.sendRequest(url, this.dialogTableDataSource.onDataReturnInitializeTable, this.dialogTable); 
            };
            yuiTree.clickEvent.subscribe(treeNodeClickHandler,this,true);
            
            var btnOk =  { 
                    text      : "确定", 
                    handler   : {fn : this.handleDialogOk, object : null, scope : this},
                    isDefault : true 
            };
            var btnCancel = {
                    text:"取消", 
                    handler:{fn : this.handleDialogCancel, object : null, scope : this}
            };
            var dialogConfig =
            {
                width               : "800px",
                fixedcenter         : true,
                visible             : false, 
                constraintoviewport : true,
                buttons             : [btnOk,btnCancel]
            }; 
            this.yuiDialog = new YAHOO.widget.Dialog(this.id, dialogConfig);
            this.yuiDialog.render();
        };
        var responseHandler = function handleResponse(p)
        {
            alert(p.responseText);
        };
        var callback = 
        {
            success  : responseHandler,
            failure  : responseHandler,
            argument : {existId : existId}
        }
        YAHOO.util.Connect.asyncRequest("GET", this.href, callback);
    },
    
    initTreeNode : function(parentNode,data)
    {
        for(var i=0; i<data.length;i++)
        {
            var node = new YAHOO.widget.TextNode(data[i],parentNode,false);
            if(YAHOO.lang.isArray(data[i].children))
            {
                this.initTreeNode(node,data[i].children);
            }
        }
    },
    handleDialogOk : function(e)
    {
        var tableObj = Fynd.UI.ComponentStack.find(this.tableId);
        if(tableObj)
        {
            var rs = tableObj.getRecordSet();
            for(var j = 0;j<this.selectedId.length;j++)
            {
                var existent = false;
                for(var i = 0;i<rs.getLength();i++)
                {
                    var existValue = rs.getRecord(i).getData(this.tableIdKey);
                    if(existValue == this.selectedId[i][this.tableIdKey])
                    {
                        existent = true;
                        break;
                    }
                }
                if(!existent)
                {
                    tableObj.addRow(this.selectedId[i]);
                }
            }
        }
        this.yuiDialog.hide();
    },
    handleDialogCancel : function(e)
    {
        this.yuiDialog.hide();
    },
    open : function
    {
        this.initSelectedId();
        this.yuiDialog.show();
    },
    close : function()
    {
        this.yuiDialog.hide();
    }
    }
};