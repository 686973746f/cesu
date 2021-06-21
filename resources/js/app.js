require('./bootstrap');
import { Chartisan, ChartisanHooks } from '@chartisan/chartjs';
import {Chart, ChartOptions } from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import 'selectize';
import 'datatables.net-bs4';

// Register the plugin to all charts:
Chart.plugins.register(ChartDataLabels);