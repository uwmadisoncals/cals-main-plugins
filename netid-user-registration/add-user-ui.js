jQuery(document).ready(function($) {

	$(".user-new-php .form-table #adduser-noconfirmation, .user-new-php .form-table #noconfirmation").prop( "checked", true ).closest("tr").addClass("adduser-noconfirmation-row");
 
	$(".user-new-php #createuser table tbody tr:first-child label, .user-new-php #adduser table tbody tr:first-child label").html("NetID <span class='description'>( required )</span>");

	$(".user-new-php #add-existing-user").next("p").text("Enter the email address or NetID of an existing user on this network to add them to this site.");
});
