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

// Register the plugin to all charts:
Chart.plugins.register(ChartDataLabels);