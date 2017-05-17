{block name=head}
<style type="text/css"></style>
{/block}{block name=body}
<div id="pagehead">
  <div class="bannerout">
    <img src="{if $listing_image}{$listing_image}{else}/images/newsdet-banner.jpg{/if}" alt="{$listing_name} banner" />
  </div>
</div>
<div id="surveycont">
  {if $verifytoken}
  <form action="/process/survey" method="POST" id="survey_cont">
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
          <input type="hidden" name="formToken" id="formToken" value="{$token}" />
          <input type="hidden" value="Survey" name="form_name" id="form_name" />
          <input type="hidden" name="timestamp" id="timestamp" value="{$timestamp}" />
          <input type="hidden" name="surveytoken-id" id="surveytoken-id" value="{$surveytoken_id}" />
          <input type="hidden" name="uid" value="{$user.id}">
        </div>
      </div>
    </div>
    {if $questions[1]}
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
          <h1>Feedback - recent purchase</h1>
        </div>
        <div class="col-md-8 col-md-offset-2 text-center">
          <p class="bold">Thinking about your recent purchase through the MedicAlert Foundation. How satisfied were you with the:</p>
        </div>
        {foreach from=$questions[1] key=k item=qset1}
        <div class="col-md-8 col-md-offset-2 form-group {if $qset1['option_group_id'] eq '3' || $qset1['option_group_id'] eq '2' || $qset1['option_group_id'] eq '4' || $qset1['option_group_id'] eq '5'}text-center questiondiv{/if} {if $qset1['option_group_id'] eq '3'}childdiv{/if} {if $qset1['option_group_id'] eq '5'}subquestion{/if}" {if $qset1['option_group_id'] eq '3'}id="childdiv1"{/if}>
          <input type="hidden" name="questionid[{$qset1['question_id']}]" value="{$qset1['question_id']}">
          {if $qset1['option_group_id'] eq '3' || $qset1['option_group_id'] eq '5' }
          <p class="bold">{$qset1['question']} {if $qset1['is_mandatory'] eq '1'}*{/if}</p>
          <textarea class="form-control" name="answer[{$qset1['question_id']}]" {if $qset1['is_mandatory'] eq '1'}required{/if}></textarea>
          <div class="error-msg help-block"></div>
          {else if $qset1['option_group_id'] eq '4'}
          <p class="opt">
            <input class="form-control" type="checkbox" name="answer[{$qset1['question_id']}]" id="answer-{$qset1['question_id']}-{$ak}" {if $qset1['is_mandatory'] eq '1'}required{/if} value="Yes">
            <label for="answer-{$qset1['question_id']}-{$ak}">{$qset1['question']}</label>
          </p>
          <div class="error-msg help-block"></div>
          {else}
          <p class="bold">{$qset1['question']} {if $qset1['is_mandatory'] eq '1'}*{/if}</p>
          {foreach from=$answers[$qset1['option_group_id']] key=ak item=aset}
          <p class="opt">
            <input class="form-control" type="radio" name="answerid[{$qset1['question_id']}]" id="answer-{$qset1['question_id']}-{$ak}" value="{$aset['option_id']}" {if $qset1['option_group_id'] eq '2'}data-childdiv="childdiv1" data-keyvalue="{if $aset['option'] eq 'Yes'}1{/if}" {/if} {if $qset1['is_mandatory'] eq '1'}required{/if}>
            <label for="answer-{$qset1['question_id']}-{$ak}">{$aset['option']}</label>
          </p>
          {/foreach}
          <div class="error-msg help-block"></div>
          {/if}
        </div>
        {/foreach}
      </div>
    </div>
    {/if} {if $questions[2]}
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
          <h1>Feedback - completed user profile</h1>
        </div>
        <div class="col-md-8 col-md-offset-2 text-center">
          <p class="bold">Thinking about the process of creating your Medicalert profile and finalising your membership. How satisfied were you with the:</p>
        </div>
        {foreach from=$questions[2] key=k item=qset1}
        <div class="col-md-8 col-md-offset-2 form-group {if $qset1['option_group_id'] eq '3' || $qset1['option_group_id'] eq '2' || $qset1['option_group_id'] eq '4'}text-center questiondiv{/if} {if $qset1['option_group_id'] eq '3'}childdiv{/if}" {if $qset1['option_group_id'] eq '3'}id="childdiv1"{/if}>
          <input type="hidden" name="questionid[{$qset1['question_id']}]" value="{$qset1['question_id']}">
          {if $qset1['option_group_id'] eq '3'}
          <p class="bold">{$qset1['question']} {if $qset1['is_mandatory'] eq '1'}*{/if}</p>
          <textarea class="form-control" name="answer[{$qset1['question_id']}]" {if $qset1['is_mandatory'] eq '1'}required{/if}></textarea>
          <div class="error-msg help-block"></div>
          {else if $qset1['option_group_id'] eq '4'}
          <p class="opt">
            <input class="form-control" type="checkbox" name="answer[{$qset1['question_id']}]" id="answer-{$qset1['question_id']}-{$ak}" {if $qset1['is_mandatory'] eq '1'}required{/if} value="Yes">
            <label for="answer-{$qset1['question_id']}-{$ak}">{$qset1['question']}</label>
          </p>
          <div class="error-msg help-block"></div>
          {else}
          <p class="bold">{$qset1['question']} {if $qset1['is_mandatory'] eq '1'}*{/if}</p>
          {foreach from=$answers[$qset1['option_group_id']] key=ak item=aset}
          <p class="opt">
            <input class="form-control" type="radio" name="answerid[{$qset1['question_id']}]" id="answer-{$qset1['question_id']}-{$ak}" value="{$aset['option_id']}" {if $qset1['option_group_id'] eq '2'}data-childdiv="childdiv1" data-keyvalue="{if $aset['option'] eq 'Yes'}1{/if}" {/if} {if $qset1['is_mandatory'] eq '1'}required{/if}>
            <label for="answer-{$qset1['question_id']}-{$ak}">{$aset['option']}</label>
          </p>
          {/foreach}
          <div class="error-msg help-block"></div>
          {/if}
        </div>
        {/foreach}
      </div>
    </div>
    {/if}
    <div class="container">
      <div style="height: 0; overflow: hidden;">
        <input value="" type="text" name="honeypot" id="honeypot" tabindex="-1" autocomplete="off">
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
          <div class="error-msg" id="form-error" {if !$error}style="display: none"{/if}>{$error}</div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-2 text-center">
          <input type="submit" value="Send my feedback" class="btn-red btn">
        </div>
      </div>
    </div>
  </form>
  {else}
  <div class="container">
    <div class="col-md-8 col-md-offset-2 text-center">
      <p class="bold">Sorry, you can't access this survey. It has already been done.</p>
    </div>
    <div class="col-md-8 col-md-offset-2 text-center">
      <h1>Thank you</h1>
    </div>
  </div>
  {/if}
</div>
{/block}{* Place additional javascript here so that it runs after General JS includes *}{block name=tail}
<script type="text/javascript">
  $(document).ready(function() {
    $(".childdiv").hide();
    $("#survey_cont").validate();
    $('.questiondiv input[type="radio"]').click(function() {
      if($(this).data('keyvalue') == '1'){
        var divId = $(this).data('childdiv');
        $("#" + divId).show();
      }else{
        var divId = $(this).data('childdiv');
        $("#" + divId).hide();
      }
    });

    

    $(".subquestion").hide();
    $('input[type="radio"]').click(function() {
      var clickedVal = $(this).val();           
      if(clickedVal == '4' || clickedVal == '5'){
        $(this).parents('.form-group').next('.form-group').show();
      }
    });
  });
</script>
{/block}
