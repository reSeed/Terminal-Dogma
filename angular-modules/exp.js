main.controller('expController',['$http','inform',function($http,inform){
	var self = this;
	
	self.addAdmin = function(userID, xp, courseID, description)
	{
		var data= {username: userID, exp: xp};
		if(courseID) data.courseID = courseID;
		if(description) data.description = description;
		
		$http.post('users/add_exp',data).then(function(response)
				{
					notifies = response.data;
					angular.forEach(notifies,function(notify)
						{
							inform.add(notify.description,{type: (notify.error ? 'danger' : 'success')});
						}		
					)
					
				},function(error)
				{
					
				});
		
//		console.log(data);
	};
		
	
}]);