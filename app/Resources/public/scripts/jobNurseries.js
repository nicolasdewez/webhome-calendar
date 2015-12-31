function JobNurseriesList (table)
{
    this.table = table;
    this.deactiveLinks = this.table.find('.appJobNurseriesList-actions .glyphicon-remove');
    this.activeLinks = this.table.find('.appJobNurseriesList-actions .glyphicon-ok');
    this.deleteLinks = this.table.find('.appJobNurseriesList-actions .glyphicon-trash');

    this.list = new List('.appJobNurseriesList-active', '.appJobNurseriesList-actions');

    this.onClickDeactivateLink = function(e){
        this.list.changeState(e, false);
    };

    this.onClickActivateLink = function(e){
        this.list.changeState(e, true);
    };

    this.onClickDeleteLink = function(e){
        this.list.deleteElement(e);
    };

    this.listenEvents = function() {
        this.deactiveLinks.click(this.onClickDeactivateLink.bind(this));
        this.activeLinks.click(this.onClickActivateLink.bind(this));
        this.deleteLinks.click(this.onClickDeleteLink.bind(this));
    };

    this.listenEvents();
}

function JobNurseriesForm(fieldSerial, fieldNumberDays)
{
    this.fieldSerial = fieldSerial;
    this.fieldNumberDays = fieldNumberDays;

    this.onClickFieldSerial = function() {
        if (!this.fieldSerial.is(':checked')) {
            this.fieldNumberDays.val(0);
            this.fieldNumberDays.attr('readonly', true);
        } else {
            this.fieldNumberDays.attr('readonly', false);
        }
    };

    this.listenEvents = function() {
        this.fieldSerial.click(this.onClickFieldSerial.bind(this));
    };

    this.onClickFieldSerial();
    this.listenEvents();
}

function JobNurseriesEdit(collectionPeriods, addPeriodLink, deletePeriodLink)
{
    this.collectionPeriods = collectionPeriods;
    this.addPeriodLink = addPeriodLink;
    this.deletePeriodLink = deletePeriodLink;

    // Calculate index for new element in collection
    this.collectionPeriods.data('index', this.collectionPeriods.find('div.appJobNurseriesEdit-period').length);

    this.onClickAddPeriodLink = function(e){
        e.preventDefault();
        var prototype = this.collectionPeriods.data('prototype');
        var index = parseInt(this.collectionPeriods.data('index'));
        var newForm = prototype.replace(/__name__/g, index);

        this.collectionPeriods.data('index', index + 1);

        var newFormLiWidget = $('<div class="col-md-6"></div>').append(newForm);
        var newDeleteLink = $('<a aria-hidden="true" title="Delete" class="glyphicon glyphicon-trash " rel="" href="#"></a>');
        var newDeleteBlock = $('<div class="col-md-6 appJobNurseriesEdit-period-remove"></div>').append(newDeleteLink);
        var newClearBlock = $('<div class="clear"></div>');
        var newFormElement = $('<div class="appJobNurseriesEdit-period"></div>').append(newFormLiWidget).append(newDeleteBlock).append(newClearBlock);

        this.collectionPeriods.append(newFormElement);

        newDeleteLink.click(this.onClickDeletePeriodLink.bind(this));
    };

    this.onClickDeletePeriodLink = function(e) {
        e.preventDefault();
        var element = $(e.currentTarget);
        element.parent().parent().remove();

        return false;
    };

    this.listenEvents = function() {
        this.addPeriodLink.click(this.onClickAddPeriodLink.bind(this));
        this.deletePeriodLink.click(this.onClickDeletePeriodLink.bind(this));
    };

    this.listenEvents();
}
