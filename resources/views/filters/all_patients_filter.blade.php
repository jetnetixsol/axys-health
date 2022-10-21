@foreach($patients as $i=>$patient)
@php
$patient['name'] = isset($patient['full_name']) ? $patient['full_name'] : '--';
$patientID = $patient['id'];
@endphp
<div class="card bdy-cd">
    <div class="card-body">
        <div class="crd-grd">
            <div class="crd-in">
                <div class="pat-pro">
                    <div class="multiple-select">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" data-name='<?= isset($patient['name']) ? $patient['name'] : '' ?>' name="patientIds[]" id="select_{{ $patient['id'] }}" value="{{ $patientID }}">
                        </div>
                    </div>
                    <div class="pro-hd">
                        <i class="las la-user-injured"></i>
                        <h4>{{ $patient['name'] }}
                            <span>
                                <label for="">Patient IR <b>{{ '#'.$patient['id'] }}</b></label>
                            </span>
                        </h4>
                    </div>
                    <div class="pro-det">
                        <p><i class="las la-hashtag"></i>{{ isset($patient['mrn']) ? $patient['mrn']  : '-' }}</p>
                        <p><i class="las la-birthday-cake"></i> {{ isset($patient['dob']) ? $patient['dob']  : '-' }}</p>
                        <p><i class="las la-envelope"></i> <a href="mailto:john@gmail.com">{{ isset($patient['email']) ? $patient['email'] : '-' }}</a></p>
                        <p><i class="las la-phone"></i> <a href="tel:(123) 321 654 7895">{{ isset($patient['mobile_number']) ? $patient['mobile_number'] : '--' }}</a></p>
                    </div>
                    <div class="pro-footer">
                        <div class="ft-grd">
                            <div class="ic-in tp-ch">
                                <span id="{{ $patientID }}_last_systolic">{{ isset($patient['last_record'][0]['systolic']) ? $patient['last_record'][0]['systolic'] : '--' }}</span>
                                <i class="las la-chart-line"></i>
                            </div>
                            <div class="ic-in bt-cht">
                                <span id="{{ $patientID }}_last_diastolic">{{ isset($patient['last_record'][0]['diastolic']) ? $patient['last_record'][0]['diastolic'] : '--' }}</span>
                                <i class="las la-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="crd-in">
                <div class="read-data" id="{{ $patientID }}_last_records">
                    @if(!empty($patient['last_record']))
                    @foreach($patient['last_record'] as $k=>$last_record)
                    <div class="infor {{ $k != 0 ? 'bot-ln' : '' }}">
                        <div class="read-grd">
                            <div class="read-in">
                                <label for="">Date</label>
                                <span class="bld">{{ isset($last_record['created_at']) ? Date('d M Y',strtotime($last_record['created_at'])) : '-' }}</span>
                            </div>
                            <div class="read-in">
                                <label for="">Time</label>
                                <span>{{ isset($last_record['created_at']) ? Date('h:i A',strtotime($last_record['created_at'])) : '-' }}</span>
                            </div>
                            <div class="read-in">
                                <label for="">Systolic BP</label>
                                <span>{{ isset($last_record['systolic']) ? $last_record['systolic'] : '-' }}<i class="las la-chart-line"></i></span>
                            </div>
                            <div class="read-in">
                                <label for="">Diastolic BP</label>
                                <span>{{ isset($last_record['diastolic']) ? $last_record['diastolic'] : '-' }}<i class="las la-chart-line rdd"></i></span>
                            </div>
                            <div class="read-in">
                                <label for="">Heart Rate</label>
                                <span>{{ isset($last_record['irregular_heartbeat']) ? $last_record['irregular_heartbeat'] : '-' }} <i class="las la-chart-line"></i></span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="infor">
                        <div class="read-grd">
                            <div class="read-in">
                                <label for="">Date</label>
                                <span class="bld">--</span>
                            </div>
                            <div class="read-in">
                                <label for="">Time</label>
                                <span>--</span>
                            </div>
                            <div class="read-in">
                                <label for="">Systolic BP</label>
                                <span>0<i class="las la-chart-line"></i></span>
                            </div>
                            <div class="read-in">
                                <label for="">Diastolic BP</label>
                                <span>0<i class="las la-chart-line rdd"></i></span>
                            </div>
                            <div class="read-in">
                                <label for="">Heart Rate</label>
                                <span>0<i class="las la-chart-line"></i></span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="crd-in">
                <div class="txt-ar">
                    <textarea name="remarks" id="remarks_{{ $patientID }}" onkeypress="remarks_(event,'{{ $patientID }}')" class="form-control" rows="5" placeholder="Enter Remarks and Press Enter to Save">{{ isset($patient['remarks']) ? $patient['remarks'] : '' }}</textarea>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary info_btn" onclick="ownToggle('<?= $i  ?>')" id="info_btn<?= $i ?>"><span>View Info</span></button>
                        @if(isset($patient['serial_number']) && Auth::guard()->user()->role === 'doctor')
                        @php $url = route('session.end',$patientID); @endphp
                        <button class="btn btn-dangerr" type="button" onclick="showEndSessionModal('{{ $url }}')"><span>End Session</span></button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- <?= $i == 0 ? '' : 'hidden' ?> -->
        <div class="bot-grd" id="info_box<?= $i ?>">
            <div class="sing-ftr">
                <div class="card ftr">
                    <div class="card-body">
                        <div class="ftr-grd">
                            <div class="ftr-in">
                                <div class="input-group">
                                    <input type="text" name="daterange" class="form-control daterange" value="" id="{{ $patientID }}_report" onchange="changeGraph('{{ $patientID }}')" />
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="las la-calendar-day"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="ftr-in">
                                <div class="btn-group wdt">
                                    <!--<button type="button" class="btn btn-primary"></button>-->
                                    <input type="text" id="{{ $patientID }}_uri" value="" hidden />
                                    <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-bs-toggle="dropdown">
                                        Export
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <!-- downloadFile('{{ $patientID }}','pdf') -->
                                        <li><a href="javascript:void(0)" id="down_pdf_{{ $patientID }}">PDF File<i class="las la-file-pdf"></i></a>
                                        </li>
                                        <!-- onclick="downloadFile('{{ $patientID }}','jpg')" -->
                                        <li><a href="javascript:void(0)" id="down_jpg_{{ $patientID }}">JPG File<i class="las la-file-image"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grd-dvc-patient">
                <div class="dvc-in-patient">
                    <div id="chart<?= $patient['id'] ?>">
                        <div id="timeline-chart<?= $patient['id'] ?>"></div>
                    </div>
                    <!-- Map Script -->
                    <script>
                        var patientID = '<?= $patient['id'] ?>';
                        var patientRecords = '<?= isset($patient['records']) ? json_encode($patient['records']) : 'undefined' ?>';
                        patientRecords = patientRecords != 'undefined' ? JSON.parse(patientRecords) : 'undefined';
                        var options<?= $patient['id']  ?> = {
                            chart: {
                                type: "area",
                                height: 300,
                                foreColor: "#999",
                                stacked: true,
                                dropShadow: {
                                    enabled: true,
                                    enabledSeries: [0],
                                    top: -2,
                                    left: 2,
                                    blur: 5,
                                    opacity: 0.06
                                }
                            },
                            colors: ['#e6d5ff', '#cbe7fd', '#f5d9b8'],
                            stroke: {
                                curve: "smooth",
                                width: 3
                            },
                            dataLabels: {
                                enabled: false
                            },
                            series: [{
                                    name: 'Systolic BP',
                                    data: generateDayWiseTimeSeries('Systolic BP', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
                                },
                                {
                                    name: 'Diastolic BP',
                                    data: generateDayWiseTimeSeries('Diastolic BP', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
                                },
                                {
                                    name: 'Heart Rate',
                                    data: generateDayWiseTimeSeries('Heart Rate', (patientRecords != undefined && patientRecords != "undefined" ? (patientRecords > 30 ? 30 : patientRecords.length) : 30), (patientRecords != undefined && patientRecords != "undefined" ? patientRecords : null))
                                }
                            ],
                            markers: {
                                size: 0,
                                strokeColor: "#fff",
                                strokeWidth: 3,
                                strokeOpacity: 1,
                                fillOpacity: 1,
                                hover: {
                                    size: 6
                                }
                            },
                            xaxis: {
                                type: "datetime",
                                axisBorder: {
                                    show: false
                                },
                                axisTicks: {
                                    show: false
                                }
                            },
                            yaxis: {
                                labels: {
                                    offsetX: 14,
                                    offsetY: -5
                                },
                                tooltip: {
                                    enabled: true
                                }
                            },
                            grid: {
                                padding: {
                                    left: -5,
                                    right: 5
                                }
                            },
                            tooltip: {
                                x: {
                                    format: "dd MMM yyyy"
                                },
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'left'
                            },
                            fill: {
                                type: "solid",
                                fillOpacity: 0.7
                            }
                        };
                        var chart<?= $patient['id']  ?> = new ApexCharts(document.querySelector("#timeline-chart" + patientID), options<?= $patient['id']  ?>);
                        chart<?= $patient['id'] ?>.render();

                        function generateDayWiseTimeSeries(s, count, records) {
                            // console.log(s,count,records);
                            // var values = [];
                            // var values = [
                            // 	[
                            // 		4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
                            // 	],
                            // 	[
                            // 		2, 3, 8, 7, 22, 16, 23, 7, 11, 5, 12, 5, 10, 4, 15, 2, 6, 10,23,12,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
                            // 	],
                            // 	[
                            // 		5, 6, 4, 9, 25, 18, 28, 10, 15, 8, 13, 7, 12, 6, 17, 4, 8, 4,3,2,4, 3, 10, 9, 29, 19, 25, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,20,11
                            // 	]
                            // ];
                            var i = 0;
                            var series = [];
                            var x = new Date().getTime();

                            if (records != null) {
                                switch (s) {
                                    case 'Systolic BP':
                                        records.map(function(val, index) {
                                            // console.log(new Date(val['date']).getTime(),val['date']);
                                            //new Date(val['date']).getTime()+86400000
                                            series.push([new Date(val['created_at']).getTime(), val['systolic']]);
                                        });
                                        break;
                                    case 'Diastolic BP':
                                        records.map(function(val, index) {
                                            // console.log(new Date(val['date']).getTime(),val['date']);
                                            series.push([new Date(val['created_at']).getTime(), val['diastolic']]);
                                        });
                                        break;
                                    case 'Heart Rate':
                                        // console.log(records);
                                        records.map(function(val, index) {
                                            // console.log("date", val["date"]);
                                            series.push([new Date(val['created_at']).getTime(), val['ihb']]);
                                        });
                                        break;
                                }
                                // var x = new Date().getTime();
                                // while (i < count) {
                                // 	series.push([x, values[s][i]]);
                                // 	x += 86400000;
                                // 	i++;
                                // }
                            }

                            return series;
                        }

                        $("#down_pdf_" + patientID).on('click', function() {
                            downloadFile(chart<?= $patient['id'] ?>, '<?= $patient['id'] ?>', 'pdf', 'single');
                        });

                        $("#down_jpg_" + patientID).on('click', function() {
                            downloadFile(chart<?= $patient['id'] ?>, '<?= $patient['id'] ?>', 'jpg', 'single');
                        });
                    </script>
                </div>
            </div>
            <div class="pt-out-grd" id="{{ $patientID }}_days_group">
                @if(!empty($patient['days_group']))
                @foreach($patient['days_group'] as $group)
                <div class="pt-out-in">
                    <div class="card-header">
                        Day {{ $loop->index + 1 }}
                    </div>
                    <div class="al-rds">
                        <div class="grd-pt">
                            <div class="pt-in">
                                <div class="pt-box ">
                                    <span class="bp-style">Systolic BP</span>
                                </div>
                            </div>
                            <div class="pt-in">
                                <div class="pt-box pt-box1">
                                    <span class="patient-detail-text cont">{{ !empty($group['average_sys']) ? $group['average_sys'] : 00 }}</span>
                                    <label for="" class="patient-detail-text">Average</label>
                                </div>
                            </div>
                            <div class="pt-in">
                                <div class="pt-box pt-box1">
                                    <span class="patient-detail-text cont">{{ !empty($group['min_sys']) ? ($group['min_sys'] % 1 != 0 ? $group['min_sys'] : round($group['min_sys'])) : 00 }}</span>
                                    <label for="" class="patient-detail-text">Min</label>
                                </div>
                            </div>
                            <div class="pt-in">
                                <div class="pt-box pt-box1">
                                    <span class="patient-detail-text cont">{{ !empty($group['max_sys']) ? ($group['max_sys'] % 1 != 0 ? $group['max_sys'] : round($group['max_sys'])) : 00 }}</span>
                                    <label for="" class="patient-detail-text">Max</label>
                                </div>
                            </div>
                        </div>
                        <div class="grd-pt mt-2">
                            <div class="pt-in">
                                <div class="pt-box ">
                                    <span class="bp-style">Diastolic BP</span>
                                </div>
                            </div>
                            <div class="pt-in">
                                <div class="pt-box pt-box2">
                                    <span class="patient-detail-text2 cont">{{ !empty($group['average_dia']) ? $group['average_dia'] : 00 }}</span>
                                    <label for="" class="patient-detail-text2">Average</label>
                                </div>
                            </div>
                            <div class="pt-in">
                                <div class="pt-box pt-box2">
                                    <span class="patient-detail-text2 cont">{{ !empty($group['min_dia']) ? ($group['min_dia'] % 1 != 0 ? $group['min_dia'] : round($group['min_dia'])) : 00 }}</span>
                                    <label for="" class="patient-detail-text2">Min</label>
                                </div>
                            </div>
                            <div class="pt-in">
                                <div class="pt-box pt-box2">
                                    <span class="patient-detail-text2 cont">{{ !empty($group['max_dia']) ? ($group['max_dia'] % 1 != 0 ? $group['max_dia'] : round($group['max_dia'])) : 00 }}</span>
                                    <label for="" class="patient-detail-text2">Max</label>
                                </div>
                            </div>
                        </div>
                        <div class="grd-pt mt-2">
                            <div class="pt-in">
                                <div class="pt-box ">
                                    <span class="bp-style">Heart Rate</span>
                                </div>
                            </div>
                            <div class="pt-in">
                                <div class="pt-box pt-box3">
                                    <span class="patient-detail-text3 cont">{{ !empty($group['average_heart']) ? $group['average_heart'] : 00 }}</span>
                                    <label for="" class="patient-detail-text3">Average</label>
                                </div>
                            </div>
                            <div class="pt-in">
                                <div class="pt-box pt-box3">
                                    <span class="patient-detail-text3 cont">{{ !empty($group['min_heart']) ? ($group['min_heart'] % 1 != 0 ? $group['min_heart'] : round($group['min_heart']))  : 00 }}</span>
                                    <label for="" class="patient-detail-text3">Min</label>
                                </div>
                            </div>
                            <div class="pt-in">
                                <div class="pt-box pt-box3">
                                    <span class="patient-detail-text3 cont">{{ !empty($group['max_heart']) ? ($group['max_heart'] % 1 != 0 ? $group['max_heart'] : round($group['max_heart'])) : 00 }}</span>
                                    <label for="" class="patient-detail-text3">Max</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach