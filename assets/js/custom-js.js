//Navbar Active ..

$( '.navbar-nav a' ).on( 'click', function () {
	$( '.navbar-nav' ).find( 'li.active' ).removeClass( 'active' );
	$( this ).parent( 'li' ).addClass( 'active' );
});

//Confirm Delete //

				$(document).ready(function(){
				    $(".delete").click(function(e){
				        if(!confirm('This will Delete Permanently!')){
				            e.preventDefault();
				            return false;
				        }
				        return true;
				    });
				});

// Toggle Listing //
var acc = document.getElementsByClassName("collapsable-list");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active-list");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
