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
  
  $(".transaction").each(function(i, obj)
  {
    $(obj).click(function() {
      updateConnections();
      }
    );
  }
  );
  
}

function updateConnections()
{
  $(".journalentryrow").each(function(i, obj)
  {
    console.log("working on " + i);
    if ($(obj).data("transaction-id")==params.transaction)
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

