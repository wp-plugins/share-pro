//FUNCTIONS
$.extend($.expr[":"], {
    "containsNC": function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
});

var ButtonDialog = {
    local_ed : 'ed',
    init : function(ed) {
        ButtonDialog.local_ed = ed;
        tinyMCEPopup.resizeToInnerSize();
    },
    insert : function insertButton(ed) { 

        var thefile = $('.sp_file_pick').val();
                                  
        // inserts the shortcode into the active editor
        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[sharepro file="'+thefile+'"]');
        
        // closes Thickbox
        tinyMCEPopup.close();
    }
};

tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);

var loading_img= '<img src="images/loading.gif" style="margin:0 auto; display:block" />';

$(function() {

    $('.sp_file_list').html(loading_img);

    $.post(sp_ajaxurl,{ action : 'spadmin_ajax' }, function(response) {
        $('.sp_file_list').html(response);
    });

    $('.next,.prev').click(function(){
        $('.sp_file_list').html(loading_img);

        action = $(this).attr('class');
        a_offset = parseInt( $('.sp_file_pag').val() );

        switch(action) {
            case 'next': var r_offset = a_offset+1; break;
            case 'prev': if(a_offset==0) { return; } var r_offset = a_offset-1;  break;
        }

        $.post(sp_ajaxurl,{ action : 'spadmin_ajax', offset : r_offset }, function(response) {
            $('.sp_file_list').html(response);
            $('.sp_file_pag').val(r_offset);
        });
    });

    $("ul.sp_file_list").on("click", "li.pick_file", function(){
        var the_file = $(this).attr('id').split('_'); the_file = the_file[1];
        $('.sp_file_pick').val(the_file);
        ButtonDialog.insert(ButtonDialog.local_ed);
    });

});