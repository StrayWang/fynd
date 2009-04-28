YAHOO.util.Event.throwErrors = true;
var Cn = {
    Chishazi : {
        Admin : {}
    }
};
Cn.Chishazi.Admin = {
    layout :null,
    menu :null
};

( function() {
    var initTopMenu = function() {
        /*
         * Define an array of object literals, each containing the data
         * necessary to create the items for a MenuBar.
         */

        var aItemData = [

                {
                    text :"<em id=\"yahoolabel\">吃啥子</em>",
                    submenu : {
                        id :"yahoo",
                        itemdata : [ "关于 Chishazi.cn", "Chishazi.cn团队", "首选项" ]
                    }

                },

                {
                    text :"File",
                    submenu : {
                        id :"filemenu",
                        itemdata : [

                                {
                                    text :"New File",
                                    helptext :"Ctrl + N"
                                },
                                "New Folder",
                                {
                                    text :"Open",
                                    helptext :"Ctrl + O"
                                },
                                {
                                    text :"Open With...",
                                    submenu : {
                                        id :"applications",
                                        itemdata : [ "Application 1",
                                                "Application 2",
                                                "Application 3",
                                                "Application 4" ]
                                    }
                                }, {
                                    text :"Print",
                                    helptext :"Ctrl + P"
                                }

                        ]
                    }

                },

                {
                    text :"Edit",
                    submenu : {
                        id :"editmenu",
                        itemdata : [

                        [ {
                            text :"Undo",
                            helptext :"Ctrl + Z"
                        }, {
                            text :"Redo",
                            helptext :"Ctrl + Y",
                            disabled :true
                        } ],

                        [ {
                            text :"Cut",
                            helptext :"Ctrl + X",
                            disabled :true
                        }, {
                            text :"Copy",
                            helptext :"Ctrl + C",
                            disabled :true
                        }, {
                            text :"Paste",
                            helptext :"Ctrl + V"
                        }, {
                            text :"Delete",
                            helptext :"Del",
                            disabled :true
                        } ],

                        [ {
                            text :"Select All",
                            helptext :"Ctrl + A"
                        } ],

                        [ {
                            text :"Find",
                            helptext :"Ctrl + F"
                        }, {
                            text :"Find Again",
                            helptext :"Ctrl + G"
                        } ]

                        ]
                    }

                },

                "View",

                "Favorites",

                "Tools",

                "Help" ];

        var menu = new YAHOO.widget.MenuBar("header1-nav-menu", {
            itemData :aItemData,
            autosubmenudisplay :true,
            hidedelay :750,
            lazyload :true,
            effect : {
                effect :YAHOO.widget.ContainerEffect.FADE,
                duration :0.25
            }
        });

        menu.render(document.getElementById("header1-nav"));
    };

    YAHOO.util.Event.onDOMReady( function() {
        var layout = new YAHOO.widget.Layout({
            units : [ {
                position :'top',
                height :28,
                body :'header1',
                scroll :null,
                zIndex :2
            }, {
                position :'right',
                header :'Right',
                width :300,
                resize :true,
                footer :'Footer',
                collapse :true,
                scroll :true,
                body :'right1',
                animate :true,
                gutter :'5'
            }, {
                position :'bottom',
                height :30,
                body :'footer1'
            }, {
                position :'left',
                header :'Left',
                width :200,
                body :'left1',
                gutter :'5',
                scroll :null,
                zIndex :1
            }, {
                position :'center',
                body :'center1',
                gutter :'5 0'
            } ]
        });
        layout.on('render', function() {
            YAHOO.util.Event.onContentReady("header1-nav", initTopMenu);
            // this.getUnitByPosition('right').collapse();
                // this.getUnitByPosition('footer').collapse();
            }, layout, true);

        layout.render();
    });

})();