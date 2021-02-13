url= window.location.pathname.split("/").pop();

$.fn.extend({
    treed: function (o) {
      var openedClass = 'fa-eye';
      var closedClass = 'glyphicon-plus-sign';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        /* initialize each of the top levels */
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this);
            branch.prepend("");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if(url=="test_case"){
                    get_test_case(e.target.innerText)
                }          
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        /* fire event from the dynamically added icon */
        tree.find('.branch .indicator').each(function(){
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
        /* fire event to open branch if the li contains an anchor instead of text */
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        /* fire event to open branch if the li contains a button instead of text */
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});
/* Initialization of treeviews */
$('#tree1').treed();


//ajax call for delete
$(document).on("click", ".record_delete", function(){
  var id = $(this).attr("data-id");
  var url_data = $(this).attr("data-url")

   Swal.fire({
     title: "Are you sure?",
     text: "The data will be deleted",
     icon: "warning",
     showCancelButton: true,
     confirmButtonColor: "#3085d6",
     cancelButtonColor: "#d33",
     confirmButtonText: "Yes, delete it!"
   }).then((result) => {
     if (result.value) {
      $.ajax({
        type: 'post',
         url:url_data+id,
        data: {
          id: id
        },
        dataType: 'json',
        success: function(res) {
         $('#list').bootstrapTable('refresh');
        },
        error: function(res) {
          $('#list').bootstrapTable('refresh');
        }
      })
       Swal.fire(
         "Deleted!",
         "selected data deleted successfully.",
         "success"
       )
     }
   })
});


