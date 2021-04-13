//Navbar Active ..

$( '.navbar-nav li' ).on( 'click', function () {
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

// Search using Name (Filter)
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

// Search using Name (Filter) for case
function myFunction1() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

//Table Sorting
// Select all Check Box
$('.selectall').click(function() {
    if ($(this).is(':checked')) {
        $('input:checkbox').attr('checked', true);
    } else {
        $('input:checkbox').attr('checked', false);
    }
});

//date Sorting
var compare = {                           // Declare compare object
  name: function(a, b) {                  // Add a method called name
    a = a.rep
    lace(/^the /i, '');          // Remove The from start of parameter
    b = b.replace(/^the /i, '');          // Remove The from start of parameter

    if (a < b) {                          // If value a is less than value b
      return -1;                          // Return -1
    } else {                              // Otherwise
      return a > b ? 1 : 0;               // If a is greater than b return 1 OR
    }                                     // if they are the same return 0
  },
  duration: function(a, b) {              // Add a method called duration
    a = a.split(':');                     // Split the time at the colon
    b = b.split(':');                     // Split the time at the colon

    a = Number(a[0]) * 60 + Number(a[1]); // Convert the time to seconds
    b = Number(b[0]) * 60 + Number(b[1]); // Convert the time to seconds

    return a - b;                         // Return a minus b
  },
  date: function(a, b) {                  // Add a method called date
    a = a.split('/');
    b = b.split('/');
    a = new Date(a[2], a[1] - 1, a[0]);   // New Date object to hold the date
    b = new Date(b[2], b[1] - 1, b[0]);   // New Date object to hold the date
    return a - b;                         // Return a minus b
  }
};

$('.sortable').each(function() {
  var $table = $(this);                     // This sortable table
  var $tbody = $table.find('tbody');        // Store table body
  var $controls = $table.find('th');        // Store table headers
  var rows = $tbody.find('tr').toArray();   // Store array containing rows

  $controls.on('click', function() {        // When user clicks on a header
    var $header = $(this);                  // Get the header
    var order = $header.data('sort');       // Get value of data-sort attribute
    var column;                             // Declare variable called column
console.log(order)
    // If selected item has ascending or descending class, reverse contents
    if ($header.is('.ascending') || $header.is('.descending')) {  
      $header.toggleClass('ascending descending');    // Toggle to other class
      $tbody.append(rows.reverse());                // Reverse the array
    } else {                                        // Otherwise perform a sort                            
      $header.addClass('ascending');                // Add class to header
      // Remove asc or desc from all other headers
      $header.siblings().removeClass('ascending descending'); 
      if (compare.hasOwnProperty(order)) {  // If compare object has method
        column = $controls.index(this);         // Search for columnâ€™s index no

        rows.sort(function(a, b) {               // Call sort() on rows array
          a = $(a).find('td').eq(column).text(); // Get text of column in row a
          b = $(b).find('td').eq(column).text(); // Get text of column in row b
          return compare[order](a, b);           // Call compare method
        });

        $tbody.append(rows);
      }
    }
  });
});