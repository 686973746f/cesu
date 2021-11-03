require('./bootstrap');
import { Chartisan, ChartisanHooks } from '@chartisan/chartjs';
import {Chart, ChartOptions } from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import 'selectize';
import 'datatables.net';
import 'datatables.net-bs4';
import 'datatables.net-fixedcolumns-bs4';
import 'datatables.net-fixedheader-bs4';
import 'datatables.net-responsive-bs4';
import 'datatables.net-searchbuilder-bs4';
import 'datatables.net-datetime';
import 'datatables.net-scroller-bs4';
import 'datatables.net-rowgroup-bs4';
import 'datatables.net-buttons-bs4';
import jsZip from 'jszip';
import 'datatables.net-buttons/js/buttons.colVis.min';
import 'datatables.net-buttons/js/dataTables.buttons.min';
import 'datatables.net-buttons/js/buttons.flash.min';
import 'datatables.net-buttons/js/buttons.html5.min';
import 'datatables.net-buttons/js/buttons.print.min';
import 'holderjs';
import 'select2';

window.JSZip = jsZip;
window.ClipboardJS = require('clipboard');

// Register the plugin to all charts:
Chart.plugins.register(ChartDataLabels);