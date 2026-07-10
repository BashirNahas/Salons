/*
 * Public booking wizard (Alpine.js component).
 *
 * The Blade view provides page data (routes, service/staff names, locale)
 * as JSON in <script type="application/json" id="booking-wizard-config">
 * — data, not code — and this file holds all of the behavior.
 *
 * Steps: service → staff (when the salon has staff) → date & time → details.
 */
(function () {
    'use strict';

    window.bookingWizard = function () {
        var el = document.getElementById('booking-wizard-config');
        var cfg = el ? JSON.parse(el.textContent) : {};

        var hasEmployees = !! cfg.hasEmployees;
        var slotsUrl = cfg.slotsUrl || '';
        var preselectedService = cfg.preselectedService || null;
        var serviceNames = cfg.serviceNames || {};
        var staffNames = cfg.staffNames || {};
        var staffLabel = cfg.staffLabel || '';
        var dateLocale = cfg.dateLocale || 'en-US';

        return {
            currentStep: 1,
            dateTimeStep: hasEmployees ? 3 : 2,
            infoStep: hasEmployees ? 4 : 3,

            selectedService: preselectedService || null,
            selectedServiceDuration: null,
            selectedEmployee: null,
            selectedDate: '',
            selectedTime: '',

            slots: [],
            slotsState: 'idle', // idle | loading | loaded | empty | error

            get serviceName() {
                return this.selectedService ? (serviceNames[this.selectedService] || '') : '';
            },
            get staffName() {
                if (! this.selectedEmployee) return '';
                return staffNames[this.selectedEmployee] || '';
            },
            get staffLine() {
                return this.staffName ? staffLabel + ' ' + this.staffName : '';
            },

            init() {
                if (preselectedService) {
                    this.selectedService = preselectedService;
                    this.currentStep = 2;
                }
            },

            selectService(id, duration) {
                this.selectedService = id;
                this.selectedServiceDuration = duration;
            },

            selectEmployee(id) {
                this.selectedEmployee = id;
            },

            nextStep() {
                this.currentStep++;
            },

            prevStep() {
                this.currentStep--;
                if (this.currentStep === this.dateTimeStep) {
                    this.selectedTime = '';
                }
            },

            today() {
                return new Date().toISOString().split('T')[0];
            },

            formatDate(dateStr) {
                if (! dateStr) return '';
                var d = new Date(dateStr + 'T00:00:00');
                return d.toLocaleDateString(dateLocale, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            },

            loadSlots() {
                if (! this.selectedService || ! this.selectedDate) return;

                this.selectedTime = '';
                this.slots = [];
                this.slotsState = 'loading';

                var url = slotsUrl
                    + '?service_id=' + encodeURIComponent(this.selectedService)
                    + '&date=' + encodeURIComponent(this.selectedDate);

                if (this.selectedEmployee) {
                    url += '&employee_id=' + encodeURIComponent(this.selectedEmployee);
                }

                fetch(url)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (! data.slots || data.slots.length === 0) {
                            this.slots = [];
                            this.slotsState = 'empty';
                            return;
                        }
                        this.slots = data.slots;
                        this.slotsState = 'loaded';
                    }.bind(this))
                    .catch(function () {
                        this.slotsState = 'error';
                    }.bind(this));
            },
        };
    };
})();
