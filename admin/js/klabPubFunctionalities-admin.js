(function( $ ) {
	'use strict';

	$(function() {

		$("#klab_fetchPublications").click(function (event) {
			event.preventDefault();
			console.log("here");
			 klabPublicationModal.openModal();

		});

		$(document).on('confirmation', '.remodal', function () {

			klabPublicationModal.savePubs();

		});

		$(document).on('closing', '.remodal', function (e) {

			klabPublicationModal.closeModal();

			});

	});

})( jQuery );


function fetch_publications_by_auth(){
	// article URL: https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=
	//var url ="https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=25081398&retmode=json"
	var existing = new Array();
	getCurrentEntriesAndFetchNew(1, existing);

};

function getCurrentEntriesAndFetchNew(page, array){

	console.log("here4");

	var author = document.getElementsByClassName("klab_publication_author");
	var publicationApiUrl ="https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&retmode=json&id=";
	var searchUrl="https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmode=json&retmax=1000&term=" + author[0].value + "[Author]+OR+" + author[0].value +"[Investigator]";

	var url =  session.root + 'wp/v2/klab_publication?per_page=100&page='+page;
	console.log("url:" +url);
	var url =  session.root + 'wp/v2/klab_publication?per_page=100&page='+page;
	jQuery.get({
		url: url,
		beforeSend: function ( xhr ) {
			//console.log( session.nonce );
			xhr.setRequestHeader( 'X-WP-Nonce', session.nonce );
		}

	}).fail(function(data, error, fail){
		if(array.length > 0) {
			fetchListOfNewPublications(array, searchUrl, publicationApiUrl);
		}
		else {
			alert("Fetching existing publications failed.")
		}
	})
	.always(function(){
		console.log('ajax done');
	})
	.done(function(getResult){
		console.log("dindindin");
		console.log(getResult);
		for (var i=0; i< getResult.length; i++){
			array.push(getResult[i].klab_publication_uid);
		}
		if (getResult.length==0){
			fetchListOfNewPublications(array, searchUrl, publicationApiUrl);

		}
		else{
			getCurrentEntriesAndFetchNew(page+1,array);
		}
	});

}

function fetchListOfNewPublications(array, searchUrl, publicationApiUrl) {
	var newPublicationIds = new Array();

	//Returns list of pubmed ids.
	jQuery.getJSON(searchUrl,function( searchData ) {
		idLen= searchData.esearchresult.idlist.length;
		for (var j=0; j< idLen; j++){
			var uid = searchData.esearchresult.idlist[j];

			//checks if entry is already in publications
			if (array.indexOf(uid)<0){
				newPublicationIds.push(uid);
			}
		}
		//console.log("pub ids: " + newPublicationIds);
		fetchNewPublicationData(newPublicationIds, publicationApiUrl);
	});

}
//TODO: Doesn't work with big chunks of data.
function fetchNewPublicationData (publicationIds, url) {

	if(publicationIds.length === 0) {
		klabPublicationModal.noNewPosts();
		return;
	}

	var publicationsData = new Array();
	var uid = publicationIds;

	jQuery.getJSON(url + uid, function (publicationData) {
		for (var k = 0; k < publicationData.result.uids.length; k++) {
			var localUID = publicationData.result.uids[k];
			var resultItem = publicationData.result[localUID];
			var auths = resultItem.authors;
			fLen = auths.length;
			text = "";
			for (i = 0; i < fLen; i++) {
				if (i > 0) {
					text += ', '
				}
				text += auths[i].name;
			}

			//var status = 'publish';

			var data = {
				title: resultItem.title,
				klab_publication_uid: localUID,
				klab_publication_pubdate: resultItem.pubdate,
				klab_publication_authors: text,
				klab_publication_source: resultItem.source,
				klab_publication_issue: resultItem.issue,
				klab_publication_volume: resultItem.volume,
				klab_publication_pages: resultItem.pages,
				klab_publication_booktitle: resultItem.booktitle,
				klab_publication_medium: resultItem.medium,
				klab_publication_edition: resultItem.edition,
				klab_publication_publisherlocation: resultItem.publisherlocation,
				klab_publication_publishername: resultItem.publishername,
				klab_publication_fulljournalname: resultItem.fulljournalname,
				status: 'publish',

			};

			publicationsData.push(data);
		}
	}).done(function () {
		klabPublicationModal.echoDataInModal(publicationsData);

	});

}

var klabPublicationModal = {

	publicationsData: null,

	openModal: function () {
		var inst = jQuery('[data-remodal-id=modal]').remodal({closeOnConfirm:false});
		console.log("here");
		inst.open();
		console.log("here");
		fetch_publications_by_auth();
	},
	closeModal: function () {

		jQuery(".klab_modalTitle").html('Fetching new data from pubmed');
		jQuery(".klab_modalContents").html('');
	},
	noNewPosts: function() {
		jQuery(".klab_modalTitle").html('No new publications.');
		jQuery(".klab_modalContents").html('');
	},

	echoDataInModal : function (publicationsData) {
		klabPublicationModal.publicationsData = publicationsData;
		jQuery(".klab_modalTitle").html('Select publications to be added on site');

		var pubsHtml = "";

		for (var i = 0; i < publicationsData.length; i++) {

			pubsHtml += '<input type="checkbox" name="klab_pubCheckbox" value="' + publicationsData[i].klab_publication_uid + '" checked >' + publicationsData[i].title + ' </input><br/>';
		}

		if (publicationsData.length === 0) {
			pubsHtml = "<p>No results found.</p>";
		} else {
			pubsHtml = '<div class="klab_pubSelection">' + pubsHtml + '</div>';
			pubsHtml += '<button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>';
			pubsHtml += '<button data-remodal-action="confirm" class="remodal-confirm" id = "klab_addPubs">Add</button>';
		}

		jQuery(".klab_modalContents").html(pubsHtml);
	},

	getChosenPubs: function() {
		var checkboxes = document.getElementsByName("klab_pubCheckbox");
		var pubsSelected = [];

		for (var i=0; i<checkboxes.length; i++) {
			if (checkboxes[i].checked) {
				pubsSelected.push(checkboxes[i].value);

			}
		}
		return pubsSelected.length > 0 ? pubsSelected : null;
	},
	filterNonSelectedPubs: function() {
		var chosenPubs = klabPublicationModal.getChosenPubs();
		var chosenPubsData = new Array();
		if (chosenPubs === null ) {
			return new Array();
		}

		for (var i=0; i<klabPublicationModal.publicationsData.length; i++) {
			if (chosenPubs.indexOf(klabPublicationModal.publicationsData[i].klab_publication_uid)>=0 ) {
				chosenPubsData.push(klabPublicationModal.publicationsData[i]);
			}
		}
		return chosenPubsData;
	},

	savePubs: function() {

		var pubs = klabPublicationModal.filterNonSelectedPubs();

		jQuery(".klab_modalTitle").html('Adding publications to site');
		jQuery(".klab_modalContents").html('');

		if (pubs.length === 0 ) {
			jQuery(".klab_modalContents").append('<p> No posts selected to add! </p>');
		}

		for (var l = 0; l < pubs.length; l++) {

			jQuery.post({
				url: session.root + 'wp/v2/klab_publication',
				data: pubs[l],
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', session.nonce);
				}

			}).fail(function (data, error, fail) {
				console.log('ajax failed' + data + error + fail)
			})
				.always(function () {
					console.log('ajax done');
				})
				.done(function (data) {
					console.log("save post done");
					jQuery(".klab_modalContents").append('<p> Publication added. </p>');
				});

		}

	}
}


