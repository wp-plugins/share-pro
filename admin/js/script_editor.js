(function() {  
    tinymce.create('tinymce.plugins.sharepro', {  
        init : function(ed, url) {              
            ed.addButton('sharepro', {  
                title : 'Share a file Professionally',  
                image : url.replace('/js','')+'/images/spicon.png',  
                cmd : 'sharepro',
                pluginurl : ajaxurl                
            });

            ed.addCommand('sharepro', function() {
                    ed.windowManager.open({
                            file : url.replace('/js','')+'/tinymce_shortcode.php', // file that contains HTML for our modal window
                            width : 500 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
                            height : 480 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
                            inline : 1
                    }, {
                            plugin_url : url
                    });
            });

        }
        
    });  
    tinymce.PluginManager.add('sharepro', tinymce.plugins.sharepro);  
}
)();

jQuery.urlParam = function(url,name){
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(url);
    if (results == null){return ''}
    return results[1] || 0;
}