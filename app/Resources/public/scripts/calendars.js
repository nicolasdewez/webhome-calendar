function CalendarsList (table)
{
    this.table = table;
    this.deactiveLinks = this.table.find('.appCalendarsList-actions .glyphicon-remove');
    this.activeLinks = this.table.find('.appCalendarsList-actions .glyphicon-ok');
    this.deleteLinks = this.table.find('.appCalendarsList-actions .glyphicon-trash');

    this.list = new List('.appCalendarsList-active', '.appCalendarsList-actions');

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


function CalendarsEdit(collectionConnections, addConnectionLink, deleteConnectionLink)
{
    this.collectionConnections = collectionConnections;
    this.addConnectionLink = addConnectionLink;
    this.deleteConnectionLink = deleteConnectionLink;

    // Calculate index for new element in collection
    this.collectionConnections.data('index', this.collectionConnections.find('select').length);

    this.onClickAddConnectionLink = function(e){
        e.preventDefault();
        var prototype = this.collectionConnections.data('prototype');
        var index = this.collectionConnections.data('index');
        var newForm = prototype.replace(/__name__/g, index);

        this.collectionConnections.data('index', index + 1);

        var newFormLi = $('<div class="col-md-10 appCalendarsEdit-element"></div>').append(newForm);
        var newDeleteLink = $('<a aria-hidden="true" title="Delete" class="glyphicon glyphicon-trash " rel="" href="#"></a>');
        var newDeleteBlock = $('<div class="col-md-2 appCalendarsEdit-remove"></div>').append(newDeleteLink);

        this.collectionConnections.append(newFormLi);
        this.collectionConnections.append(newDeleteBlock);

        newDeleteLink.click(this.onClickDeleteConnectionLink.bind(this));
    };

    this.onClickDeleteConnectionLink = function(e) {
        e.preventDefault();
        var element = $(e.currentTarget);
        element.parent().prev().remove();
        element.parent().remove();

        return false;
    };

    this.listenEvents = function() {
        this.addConnectionLink.click(this.onClickAddConnectionLink.bind(this));
        this.deleteConnectionLink.click(this.onClickDeleteConnectionLink.bind(this));
    };

    this.listenEvents();
}
