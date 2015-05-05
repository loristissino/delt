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
    $(obj).click(function() {
      updateConnections();
      }
    );
  }
  );
  
  //$("#journalentries_shown").hide();
  
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
  /*
  $(".showjournalentries").click(function()
    {
      $("#journalenties_shown").toggle(200);
      return false;
    }
  );
  */
}



function updateConnections()
{
  $(".journalentryrow").each(function(i, obj)
  {
    console.log("working on " + i);
    if ($(obj).data("transaction-id")==params.transaction && !$(obj).hasClass("excluded"))
    {
      $(obj).addClass('connected');
      $(obj).css('background-color',  $(obj).hasClass('even') ? '#FFFFCC':'#FFFF99');
    }
    else
    {
      $(obj).removeClass('connected');
      $(obj).css('background-color', '');
    }
  }
  );
}

