function execute(params)
{
  if(params.transaction)
  {
    $("#challenge_context").hide();
  }
  else
  {
    // ...
  }
  
  updateConnections();
  addLinks(params);
  
  $(".transaction").each(function(i, obj)
  {
    $(obj).mouseenter(function() {
      console.log("entered");
      $('#quicklinks' + $(obj).data('id')).show();
      }
    );
    $(obj).mouseleave(function() {
      $('#quicklinks' + $(obj).data('id')).hide();
      }
    );
  }
  );
  
  
  $("#challenge .transaction.current").attr('style', 'width: 680px').pin();
  
}

function addLinks(params)
{
  $("#challenge_commands").hide();
  
  $("#challenge_icon").attr('title', params.i18n.icon_title_toggles);

  $("#challenge_commands").html(
    [
      '<a href="#" id="toggle_firm">' + params.i18n.toggle_firm + '</a>',
      '<a href="#" id="toggle_context">' + params.i18n.toggle_context + '</a>',
      '<a href="#" id="toggle_transactions">' + params.i18n.toggle_transactions + '</a>',
    ].join(" - ")
    );

  $("#challenge_icon").click(function()
  {
     $("#challenge_commands").toggle(200);
  }
  );
    
  $("#toggle_context").click(function()
    {
      $("#challenge_context").toggle(500);
    }
  );
  $("#toggle_firm").click(function()
    {
      $("#challenge_firm").toggle(500);
    }
  );
  $("#toggle_transactions").click(function()
    {
      $("#challenge_transactions").toggle(500);
    }
  );
  $(".checks").click(function()
	{
	  $(".checks").hide(500);
	  return false;
	}
  );
  $(".transaction").click(function()
	{
	  $(".checks").show(500);
	}
  );
  
  /*
  $(".showjournalentries").click(function()
    {
      $("#journalenties_shown").toggle(200);
      return false;
    }
  );
  */
}

function updateConnections(id)
{
  $(".journalentryrow").each(function(i, obj)
  {
    //console.log("working on " + i);
    
    if (!$(obj).hasClass("excluded"))
    {
      if (params.transaction && $(obj).data("transaction-id")==params.transaction)
      {
        $(obj).css('background-color',  $(obj).hasClass('even') ? '#FFFFCC':'#FFFF99');
        $(obj).attr('title', params.i18n.linked_to_current);
      }
      else if (!$(obj).data("transaction-id"))
      {
        $(obj).css('background-color',  $(obj).hasClass('even') ? '#FFDDDD':'#FFBBBB');
        $(obj).attr('title', params.i18n.not_linked);
      }
      else
      {
        $(obj).css('background-color', '');
      }
    }
  }
  );
}

