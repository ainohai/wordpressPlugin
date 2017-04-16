(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

function fetch_publications_by_auth(){
	// article URL: https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=
	//var url ="https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=25081398&retmode=json"
	var existing = new Array();
	getCurrentEntries(1, existing);

};

function getCurrentEntries(page, array){
	var url ="https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&retmode=json&id=";
	var searchUrl="https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmode=json&retmax=1000&term=klefstrom+j[author]";
	jQuery.get({
		url: session.root + 'wp/v2/klab_publication?per_page=100&page='+page,
		beforeSend: function ( xhr ) {
			console.log( session.nonce );
			xhr.setRequestHeader( 'X-WP-Nonce', session.nonce );
		}

	}).fail(function(data, error, fail){
		console.log('ajax failed' + data + error + fail)
	})
	.always(function(){
		console.log('ajax done');
	})
	.done(function(getResult){
		for (var i=0; i< getResult.length; i++){
			array.push(getResult[i].klab_publication_uid);
		}
		if (getResult.length==0){
		jQuery.getJSON(searchUrl,function( searchData ) {
			idLen= searchData.esearchresult.idlist.length;
			for (var j=0; j< idLen; j++){
				var uid = searchData.esearchresult.idlist[j];
				if (array.indexOf(uid)<0){
					data = jQuery.getJSON(url+uid,resultToPost).done(function(dataa) {
					});
				}
			}
		});
		}
		else{
			getCurrentEntries(page+1,array);
		}
	});
}

function resultToPost(data2 ) {
	for (var k=0; k < data2.result.uids.length; k++){
		var	localUID = data2.result.uids[k];
		var resultItem = data2.result[localUID];
		var auths = resultItem.authors;
		fLen = auths.length;
		text = "";
		for (i = 0; i < fLen; i++) {
			if (i>0){
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
		//console.log('done' + test.nonce);
		//console.log(test.root + 'wp/v2/klab_publication');
		jQuery.post({
			url: session.root + 'wp/v2/klab_publication',
			data: data,
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader( 'X-WP-Nonce', session.nonce );
			}

		}).fail(function(data, error, fail){
			console.log('ajax failed' + data + error + fail)
		})
		.always(function(){
			console.log('ajax done');
		})
		.done(function(data){
			//console.log(data.id);
		});

	}

}


