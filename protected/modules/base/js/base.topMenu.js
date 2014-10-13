jQuery(document).ready(function($) {
	var lastOpenedPanel;
	$('.topMenuLink').click(function(e){
		e.preventDefault();
		var panelId = '#'+$(this).attr('name');
		if(lastOpenedPanel && lastOpenedPanel != panelId)
		{
			$(lastOpenedPanel).slideUp('fast', function(){
				$(panelId).slideToggle('fast');
			});
		}
		else
			$(panelId).slideToggle('fast');		
		lastOpenedPanel = panelId;
	});
});