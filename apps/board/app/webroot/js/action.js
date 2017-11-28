function htmlEscape(string) {
  if(typeof string !== 'string') {
    return string;
  }
  return string.replace(/[&'`"<>]/g, function(match) {
    return {
      '&': '&amp;',
      "'": '&#x27;',
      '`': '&#x60;',
      '"': '&quot;',
      '<': '&lt;',
      '>': '&gt;',
    }[match]
  });
}

function confirmBox()
{
    // ボタン押下時の処理
    if (window.confirm('データを削除しますか？')) {
        return true;
    } else {
        window.alert('キャンセルしました。');
        return false;
    }
}

var MODAL = {
	set:function(Id, modelname){
		var defer = new $.Deferred();
		$('form ins').remove();
		$.ajax({
			url : '/board/'+modelname+'s/get'+modelname+'Ajax/'+Id,
			//data : userId,
			type : 'get',
			success : function (json) {
				var data = $.parseJSON(json);
				if(modelname == 'User'){
					MODAL.processUser(modelname, data);
				} else if(modelname == 'Genre'){
					MODAL.processGenre(modelname, data);
				} else if(modelname == 'Topic'){
					MODAL.processGenre(modelname, data);
				} else if(modelname == 'Comment'){
					MODAL.processComment(modelname, data);
				} else{
					console.log('modelname dont exist');
				}
			},
			error : function (textStatus, errorThrown) {
				console.log('fail');
		        console.log(textStatus); // リクエスト結果を表す文字列
		        console.log(errorThrown); // 例外オブジェクト
			},
			complete : function () {
				console.log('ajax finish');
				defer.resolve(); //Deferred defer終了
			}
		});
		return defer;
	},
	save:function(modelname){
		var params = $('#'+modelname+'PostSearchForm').serialize();
		$('form ins').remove();
		$.ajax({
			url : '/board/'+modelname+'s/save'+modelname+'Ajax/',
			data : params,
			type : 'post',
			success : function (json) {
				var data = $.parseJSON(json);
				if(!data.success){
					// バリデーションエラーをそれぞれのフォームへ表示
					for( elem in data.errors.validation){
						$('div.errors-validation-'+elem).append('<ins>'+data.errors.validation[elem]+'</ins>');
					}
				} else{
					if(modelname == 'Comment'){
						location.href='/board/'+modelname+'s/index/'+data.results.topic_id;
					}else{
						location.href='/board/'+modelname+'s/search/';
					}
				}
				console.log(json);
			},
			error : function (textStatus, errorThrown) {
				console.log('connection failed');
		        console.log(textStatus); // リクエスト結果を表す文字列
		        console.log(errorThrown); // 例外オブジェクト
			},
			complete : function () {
				console.log('save finish');
			}
		});
	},
	setCommentTree:function(commentId, commentSeq){
		$("ins.seq").remove();
		var defer = new $.Deferred();
		var commentTree = '';
		$.ajax({
			url : '/board/Comments/getCommentTreeAjax/'+commentId,
			//data : userId,
			type : 'get',
			success : function (json) {
				var data = $.parseJSON(json);
				var commentList = data.results;
				if(data.success){
					for( elem in commentList ){	//elem : commentList['Comment'][elem]
						commentTree += '<div class="comment-info">No:'+commentList[elem].Comment.sequential_number;
						commentTree += '　投稿日時:'+commentList[elem].Comment.created;
						commentTree += '　投稿者:'+commentList[elem].Comment.user_name;
						commentTree += '</div><pre>'+htmlEscape(commentList[elem].Comment.comment)+'</pre>';
					}
				console.log('done');
				console.log(data);
				} else{
					commentTree = 'このコメントにはツリーがありません';
					console.log(data);
				}
			},
			error : function (textStatus, errorThrown) {
				commentTree = 'Connection error...';
				console.log('fail');
		        console.log(textStatus); // リクエスト結果を表す文字列
		        console.log(errorThrown); // 例外オブジェクト
			},
			complete : function () {
				$("#CommentTreeTitle").append('<ins class="seq">'+commentSeq+'</ins>');
				$("#CommentTreeSwap").html(commentTree);
				console.log('ajax finish');
				defer.resolve(); //Deferred defer終了
			}
		});
		return defer;
	},
	processUser:function(modelname, data){
		if(data.success){
			$('#UserPostId').val(data.results.User.id);
			$('#UserPostUserName').val(data.results.User.user_name);
			$('#UserPostUserPassword').val('');
			if(data.results.User.admin==1){
				$('#UserPostAdmin').prop('checked', true);
				console.log('checked');
			} else{
				$('#UserPostAdmin').prop('checked', false);
			}
		} else{
			// 入力フォーム初期化
			$('#UserPostId').val('undefined');
			$('#UserPostUserName').val('');
			$('#UserPostUserPassword').val('');
			$('#UserPostAdmin').prop('checked', false);
		}
	},
	processGenre:function(modelname, data){
		if(data.success){
			$('#GenrePostGenreName').val(data.results.Genre.genre_name);
		} else{
			// 入力フォーム初期化
			$('#GenrePostGenreName').val('');
		}
	},
	processTopic:function(modelname, data){
		$('#TopicPostTopicName').val('');
		$('#TopicPostComment').val('');
	},
	processComment:function(modelname, data){
		$('#CommentPostComment').val('');
	},
	init: function (modelname){
		// モーダルイベント
		$('.openFilledModal').on('click', function(event) {
			var id  = $(this).attr("name");
			var defer = MODAL.set( id, modelname);
			$('#'+modelname+'PostId').val(id);
			defer.promise().then(function() { //Deferred defer終了宣言後にthen開始
				$('#'+modelname+'Modal').fadeIn();
			});
		});
		$('.openReplyModal').on('click', function(event) {
			var parentId  = $(this).attr("name");
			$('#CommentPostParentId').val(parentId);
			$('#'+modelname+'Modal').fadeIn();
		});
		$('.openModal').on('click', function(event) {
			var id  = $(this).attr("name");
			var defer = MODAL.set( null, modelname);
			defer.promise().then(function() { //Deferred defer終了宣言後にthen開始
				$('#'+modelname+'Modal').fadeIn();
			});
		});
		$('.closeModal').on('click', function() {
			$('#'+modelname+'Modal').fadeOut();
		});

		// セーブボタン
		$('#'+modelname+'SaveButton').on('click', function () {
				MODAL.save(modelname);
		});

		//ツリー表示ボタン
		$('.openCommentTreeView').on('click', function () {
			var commentId = $(this).attr("num");
			var commentSeq = $(this).attr("name");
			var defer = MODAL.setCommentTree(commentId, commentSeq);
			defer.promise().then(function() { //Deferred defer終了宣言後にthen開始
				$('#CommentTreeWindow').fadeIn();
			});
		});
		$('.closeCommentTreeView').on('click', function () {
			$('#CommentTreeWindow').fadeOut();
		});
	}

};