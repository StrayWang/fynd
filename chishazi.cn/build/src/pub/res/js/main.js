var Cn = {};
Cn.Chishazi = {};
Cn.Chishazi.Admin =
{
    layout: null
};

(function() {
    var loader = new YAHOO.util.YUILoader();
    loader.require = ['menu'];
    loader.onSuccess = function() {
        Cn.Chishazi.Admin.layout = new YAHOO.widget.Layout({
            minWidth: 1000,
            units: [
                { position: 'top',
                    height: 50,
                    resize: false,
                    body: 'header'
                },
                { position: 'left',
                    width: 150,
                    resize: true,
                    body: 'left'
                },
                { position: 'footer',
                    height: 30
                },
                { position: 'right',
                    width: 150
                },
                { position: 'center'
                }
            ]
        });

        Cn.Chishazi.Admin.layout.on('render', function() {
            Cn.Chishazi.Admin.layout.getUnitByPosition('right').collapse();
            Cn.Chishazi.Admin.layout.getUnitByPosition('footer').collapse();
        }, Cn.Chishazi.Admin.layout, true);

        Cn.Chishazi.Admin.layout.render();


    };
})();