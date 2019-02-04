<style>
    @import url('https://fonts.googleapis.com/css?family=Anonymous+Pro');

    .container-hl * {
        margin: 0;
        padding: 0;
        font-family: 'Anonymous Pro', monospace;
        font-size: 0.9rem;
        color: #e0e0e0;
    }

    .container-hl {
        display: flex;
        width: 100%;
        margin: 4px 16px;
    }

    .background-error-hl {
        position: relative;
        z-index: 999;
        max-width: 100%;
        background-color: #212121;
        margin: 4px 0px;
        padding: 10px 12px;
    }
    .background-error-hl .error-header-hl {
        background-color: #D73B2B;
        padding: 4px 8px;
    }
    .background-error-hl .debug-header-hl {
        background-color: #EF590D;
    }
    .background-error-hl .error-content-hl {
        margin-top: 6px;
    }
    .background-error-hl .error-content-hl p.p-hl,
    .background-error-hl .error-content-hl p.error-text
    {
        color: #ACCBCD;
    }
    .background-error-hl .error-content-hl p.p-hl strong {
        color: #53BDAD;
    }
    .background-error-hl summary:focus {
        outline: 0;
        outline-offset: 0;
    }
    .background-error-hl details.offset {
        margin: 4px 0;
    }
    .background-error-hl .error-content-hl details>summary:not(:first-child) {
        margin-left: 16px;
    }
    .background-error-hl .error-content-hl details>details {
        margin-left: 17px;
    }
    .background-error-hl .error-content-hl details>.array {
        background-color: #880e4f;
    }
    .background-error-hl .error-content-hl details>.object {
        background-color: #311b92;
    }
    .background-error-hl .error-content-hl details>.resource {
        background-color: #795548;
    }
    .background-error-hl .error-content-hl details table {
        margin-left: 16px;
    }
    .background-error-hl .error-content-hl details table td {
        padding: 2px 6px 2px 0;
    }
    .background-error-hl .error-content-hl details table td.string,
    .background-error-hl .error-content-hl details summary .string {
        color: #ff6f00;
    }
    .background-error-hl .error-content-hl details table td.integer {
        color: #00bcd4;
    }
    .background-error-hl .error-content-hl details table td.double {
        color: #039be5;
    }
    .background-error-hl .error-content-hl details table td.boolean {
         color: #f9a825;
     }
    .background-error-hl .error-content-hl details table td.null,
    .background-error-hl .error-content-hl details table td.unknown-type {
        color: #d50000;
    }
    .background-error-hl .error-content-hl details.long-string {
        margin-left: 0;
    }
    .background-error-hl .error-content-hl details.long-string>summary:not(:first-child) {
        background-color: #757575;
    }
    .debug-footer-hl {
        background-color: #33691e;
        padding: 0 4px;
    }
</style>