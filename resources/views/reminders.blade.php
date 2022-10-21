<?php $page ='reminders'; ?>
@include('header')
	<!-- full calendar -->
	<link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}" />


	<link rel="stylesheet" href="{{ asset('assets/bundles/flatpicker/css/flatpickr.min.css') }}" />
	<link href="{{ asset('assets/bundles/fullcalendar/packages/core/main.min.css') }}" rel='stylesheet' />
	<link href="{{ asset('assets/bundles/fullcalendar/packages/daygrid/main.min.css') }}" rel='stylesheet' />
	<link href="{{ asset('assets/bundles/fullcalendar/packages/timegrid/main.min.css') }}" rel='stylesheet' />
	<link href="{{ asset('assets/css/fullcalendar.css') }}" rel='stylesheet' />


	<!-- start page content -->
	<div class="page-content-wrapper">
		<div class="page-content pdn clnd">
			<div class="row">
				<div class="col-md-10 offset-md-1 col-sm-12">
					<div class="card">
						<div class="card-head">
							<h4 class="card-title">Reminder</h4>
							<button class="btn btn-primary" data-bs-target="#exampleModal1" data-bs-toggle="modal" style="float: right;">Add Reminder</button>
						</div>
						<div class="card-body">
							@if(session()->has('success'))
							<div class="alert alert-success">
								{{ session()->get('success') }}
							</div>
							@endif

							@if(session()->has('fail'))
							<div class="alert alert-danger">
								{{ session()->get('fail') }}
							</div>
							@endif
							<div class="row">
								<div class="col-md-3 col-sm-12 dis-n">
									<div class="card-box">
										<div class="card-body">
											<div id='external-events'>

											</div>
										</div>
									</div>

								</div>
								<div class="card">
									<div class="card-body">
										<div class="panel-body">
											<div id="calendar" class="has-toolbar"> </div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end page content -->

	<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addEventTitle">Add Reminder</h5>
					<!-- <h5 class="modal-title" id="editEventTitle">Edit Reminder</h5> -->
					<button type="button" class="btn-close" data-bs-dismiss="modal"
							aria-label="Close"></button>
				</div>
				<div class="modal-body">
					{{-- <!-- <form>	 -->
						<!-- <div class="modal-footer bg-whitesmoke pr-0">
							<button class="d-none" type="submit" class="btn btn-round btn-primary" id="add-event"> Add Reminder</button>
							<button class="d-none" type="button" class="btn btn-round btn-primary" id="edit-event">Edit Event</button> 
						</div> -->
					<!-- </form> --> --}}
					<form method="post" action="{{ route('insertReminder') }}">
						@csrf
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Title</label>
									<div class="input-group">
										<input type="text" required value="{{ old('title') }}" class="form-control" name="title" id="title">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mb-4">
								<label>Category</label>
								<select class="form-select form-control" name="category" id="categorySelect">
									<option id="work" value="Work">Work</option>
									<option id="personal" value="Personal">Personal</option>
									<option id="important" value="Important">Important</option>
									<option id="travel" value="Travel">Travel</option>
									<option id="friends" value="Friends">Friends</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group">
									<label>Start Date</label>
									<input type="date" required name="start_date" value="{{ old('start_date') }}" class="form-control datetimepicker" placeholder="Start Date" name="starts_at" id="starts-at">
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									<label>End Date</label>
									<input type="date" required name="end_date" value="{{ old('end_date') }}" class="form-control datetimepicker" placeholder="End Date" name="ends_at" id="ends-at">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Event Details</label>
									<textarea id="eventDetails" name="event_details" class="form-control">{{ old('event_details')  }}</textarea>
								</div>
							</div>
						</div>
						<div class="modal-footer bg-whitesmoke pr-0">
							<button type="submit" class="btn btn-round btn-primary">Add Reminder</button>
							<button type="button" id="close" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Flat date and time picker -->
	<script src="{{ asset('assets/js/jquery.nice-select.js') }}"></script>

	<script>
		$(document).ready(function() {
			$('select').niceSelect();
		});
	</script>
	<script>
		var reminders = '{{ $reminders }}';
		reminders = JSON.parse(reminders.replace(/&quot;/g,'"'));
	</script>
	<script src="{{ asset('assets/bundles/fullcalendar/packages/core/main.min.js') }}"></script>
	<script src="{{ asset('assets/bundles/fullcalendar/packages/interaction/main.min.js') }}"></script>
	<script src="{{ asset('assets/bundles/fullcalendar/packages/daygrid/main.min.js') }}"></script>
	<script src="{{ asset('assets/bundles/fullcalendar/packages/timegrid/main.min.js') }}"></script>
	<script src="{{ asset('assets/data/calendar-data.js') }}"></script>
	<script>
		// var calendar;
		var Draggable = FullCalendarInteraction.Draggable;
		// var date_picker;
		var containerEl = document.getElementById("external-events");
		var checkbox = document.getElementById("drop-remove");
		var addEvent = document.getElementById("add-event");
		var editEvent = document.getElementById("edit-event");
		var addEventTitle = document.getElementById("addEventTitle");
		var editEventTitle = document.getElementById("editEventTitle");

		var date = new Date();
		var day = date.getDate();
		var month = date.getMonth();
		var year = date.getFullYear();

		(this.$eventModal = $("#event-modal")),
		new Draggable(containerEl, {
			itemSelector: ".fc-event",
			eventData: function (eventEl) {
			return {
				title: eventEl.innerText,
				stick: true,
				className: eventEl.dataset.class,
			};
			},
		});

		$(document).ready(function () {
		initCalendar();
		addEvetClick();
		editEvetClick();
		flatpickr("#starts-at", {
			enableTime: true,
			allowInput: true,
			dateFormat: "Y-m-d H:i",
			onOpen: function (selectedDates, dateStr, instance) {
			instance.setDate(instance.input.value, false);
			},
		});
		flatpickr("#ends-at", {
			enableTime: true,
			allowInput: true,
			dateFormat: "Y-m-d H:i",
			onOpen: function (selectedDates, dateStr, instance) {
			instance.setDate(instance.input.value, false);
			},
		});
		});

		function initCalendar() {
		var calendarEl = $("#calendar").get(0);
		calendar = new FullCalendar.Calendar(calendarEl, {
			plugins: ["interaction", "dayGrid", "timeGrid"],
			header: {
			left: "prev,next today",
			center: "title",
			right: "dayGridMonth,timeGridWeek,timeGridDay",
			},
			editable: true,
			droppable: true,
			navLinks: true,
			eventLimit: true,
			weekNumberCalculation: "ISO",
			displayEventEnd: true,
			lazyFetching: true,
			selectable: true,
			eventMouseEnter: function (info) {
			$(info.el).attr("id", info.event.id);

			$("#" + info.event.id).popover({
				template:
				'<div class="popover" role="tooltip"><div class="arrow"></div><h4 class="popover-header"></h4><div class="popover-body"></div></div>',
				title: info.event.title,
				content: info.event.extendedProps.description,
				placement: "top",
				html: true,
			});
			$("#" + info.event.id).popover("show");
			$(".popover .popover-header").css(
				"color",
				$(info.el).css("background-color")
			);
			},
			eventMouseLeave: function (info) {
			$("#" + info.event.id).popover("hide");
			},
			drop: function (info) {
			if (checkbox.checked) {
				info.draggedEl.parentNode.removeChild(info.draggedEl);
			}
			},
			views: {
			dayGridMonth: {
				eventLimit: 3,
			},
			},

			events: [
				<?php if (!empty($reminders)) {
                        foreach ($reminders as $reminder) { ?> {
                                // id: "reminder_</?= $reminder['id'] ?>",
                                // title: "Reminder: <?= $reminder['title'] ?>",
                                // start: "</?= Date('Y-m-d', strtotime($reminder['date'])) ?>",
                                // end: "</?= Date('Y-m-d',strtotime(Date('Y-m-d').'+3 Days')) ?>",
                                // color: '#000000',
								id: "<?= $reminder['id'] ?>",
								title: "<?= $reminder['title'] ?>",
								// new Date(val.start_date).getYear()+','+new Date(val.start_date).getMonth()+','+new Date(val.start_date).getDate()
								//new Date(val.end_date).getYear()+','+new Date(val.end_date).getMonth()+','+new Date(val.end_date).getDate()
								start: "<?= Date('Y-m-d', strtotime($reminder['start_date'])) ?>",
								end: "<?= Date('Y-m-d', strtotime($reminder['end_date'].'+1 Day')) ?>",
								className: "fc-event-success",
								description: "<?= $reminder['event_details'].(isset($reminder['category']) ? 'Category: '.$reminder['category'] : '') ?>",
                            },
                <?php   }
                } ?>

			],//events()

			select: function (start, end) {
			addEvent.style.display = "block";
			editEvent.style.display = "none";
			addEventTitle.style.display = "block";
			editEventTitle.style.display = "none";

			clearModalForm();
			$(".modal").modal("show");
			},
			eventClick: function (info) {
			addEvent.style.display = "none";
			editEvent.style.display = "block";
			addEventTitle.style.display = "none";
			editEventTitle.style.display = "block";

			//let startDate = moment(info.event.start).format("YYYY-MM-DD HH:mm:ss");
			//let endDate = moment(info.event.end).format("YYYY-MM-DD HH:mm:ss");

			// console.log(info.event.extendedProps.description);
			$(".modal").modal("show"); 
			$(".modal").find("#id").val(info.event.id);
			$(".modal").find("#title").val(info.event.title);
			$(".modal").find("#starts-at").val(startDate);
			$(".modal").find("#ends-at").val(endDate);
			$("#categorySelect").val(info.event.classNames[0]);
			$(".modal")
				.find("#eventDetails")
				.val(info.event.extendedProps.description);
			},
		});

		calendar.render();
		}

		function clearModalForm() {
		var input = document.querySelectorAll('input[type="text"]');
		var textarea = document.getElementsByTagName("textarea");
		for (i = 0; i < input.length; i++) {
			input[i].value = "";
		}
		for (j = 0; j < textarea.length; j++) {
			textarea[j].value = "";
			i;
		}
		}

		function addEvetClick() {
		$("#add-event").on("click", function (event) {
			var title = $("#title").val();
			var eventDetails = document.getElementById("eventDetails").value;
			var category = $("#categorySelect").find(":selected").val();
			var randomID = randomIDGenerate(
			10,
			"0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
			);
			calendar.addEvent({
			id: randomID,
			title: title,
			start: $("#starts-at").val(),
			end: $("#ends-at").val(),
			className: category,
			description: eventDetails,
			});
			// Clear modal inputs
			$(".modal").find("input").val("");
			// hide modal
			$(".modal").modal("hide");
		});
		}

		function editEvetClick() {
		$("#edit-event")
			.off("click")
			.on("click", function (event) {
			event.preventDefault();
			var category = $("#categorySelect").find(":selected").val();

			var event2 = calendar.getEventById(document.getElementById("id").value);

			var eventDetails = document.getElementById("eventDetails").value;
			var category = $("#categorySelect").find(":selected").val();

			event2.setExtendedProp("id", document.getElementById("id").value + "");
			event2.setProp("title", document.getElementById("title").value + "");
			event2.setStart(
				moment(document.getElementById("starts-at").value).format(
				"YYYY-MM-DD HH:mm:ss"
				)
			);
			event2.setEnd(
				moment(document.getElementById("ends-at").value).format(
				"YYYY-MM-DD HH:mm:ss"
				)
			);
			event2.setProp("classNames", category);
			event2.setExtendedProp("description", eventDetails);
			$(".modal").modal("hide");
			});
		}
		function randomIDGenerate(length, chars) {
		var result = "";
		for (var i = length; i > 0; --i)
			result += chars[Math.round(Math.random() * (chars.length - 1))];
		return result;
		}

		function events() {
		// console.log(reminders,"2");
		if(reminders.length > 0){
				var elements = [];
				// reminders =  Object.entries(reminders);
				// console.log("reminders",reminders);
				reminders.forEach(function(val,index){
					elements.push({
					id: val.id,
					title: val.title,
					// new Date(val.start_date).getYear()+','+new Date(val.start_date).getMonth()+','+new Date(val.start_date).getDate()
					//new Date(val.end_date).getYear()+','+new Date(val.end_date).getMonth()+','+new Date(val.end_date).getDate()
					start: new Date(2022,7, 11, 0, 0),
					end: new Date(2022,7, 12, 23, 59),
					className: "fc-event-success",
					description: val.event_details,
					});
				});
				console.log('el',new Date(year,month, 11, 0, 0),elements);
				// Object.entries(reminders).map(item =>{
				// 	console.log("Items ",item);
				// });
				// console.log("Elements ",elements);
				// console.log(reminders);
				return [elements];
				return [
				//   // {
				//   //  id: "event1",
				//   //  title: "All Day Event",
				//   //  start: new Date(year, month, 1, 0, 0),
				//   //  end: new Date(year, month, 1, 23, 59),
				//   //  className: "fc-event-success",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see.",
				//   // },
				//   //{
				//   //  id: "event2",
				//   //  title: "Break",
				//   //  start: new Date(year, month, day + 28, 16, 0),
				//   //  end: new Date(year, month, day + 29, 20, 0),
				//   //  allDay: false,
				//   //  className: "fc-event-primary",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see. ",
				//   //},
				//   //{
				//   //  id: "event3",
				//   //  title: "Shopping",
				//   //  start: new Date(year, month, day + 4, 12, 0),
				//   //  end: new Date(year, month, day + 4, 20, 0),
				//   //  allDay: false,
				//   //  className: "fc-event-warning",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see. ",
				//   //},
				//   //{
				//   //  id: "event4",
				//   //  title: "Meeting",
				//   //  start: new Date(year, month, day + 14, 10, 30),
				//   //  end: new Date(year, month, day + 16, 20, 0),
				//   //  allDay: false,
				//   //  className: "fc-event-success",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see.",
				//   //},
				//   //{
				//   //  id: "event5",
				//   //  title: "Lunch",
				//   //  start: new Date(year, month, day, 11, 0),
				//   //  end: new Date(year, month, day, 14, 0),
				//   //  allDay: false,
				//   //  className: "fc-event-primary",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see.",
				//   //},
				//   //{
				//   //  id: "event6",
				//   //  title: "Office Party",
				//   //  start: new Date(year, month, day + 2, 12, 30),
				//   //  end: new Date(year, month, day + 2, 14, 30),
				//   //  allDay: false,
				//   //  className: "fc-event-success",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see.",
				//   //},
				//   //{
				//   //  id: "event7",
				//   //  title: "Birthday Party",
				//   //  start: new Date(year, month, day + 17, 19, 0),
				//   //  end: new Date(year, month, day + 17, 19, 30),
				//   //  allDay: false,
				//   //  className: "fc-event-warning",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see.",
				//   //},
				//   //{
				//   //  id: "event8",
				//   //  title: "Go to Delhi",
				//   //  start: new Date(year, month, day + -5, 10, 0),
				//   //  end: new Date(year, month, day + -4, 10, 30),
				//   //  allDay: false,
				//   //  className: "fc-event-danger",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see.",
				//   //},
				//   //{
				//   //  id: "event9",
				//   //  title: "Get To Gather",
				//   //  start: new Date(year, month, day + 6, 10, 0),
				//   //  end: new Date(year, month, day + 7, 10, 30),
				//   //  allDay: false,
				//   //  className: "fc-event-info",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see.",
				//   //},
				//   //{
				//   //  id: "event10",
				//   //  title: "Collage Party",
				//   //  start: new Date(year, month, day + 20, 10, 0),
				//   //  end: new Date(year, month, day + 20, 10, 30),
				//   //  allDay: false,
				//   //  className: "fc-event-info",
				//   //  description:
				//   //    "Her extensive perceived may any sincerity extremity. Indeed add rather may pretty see.",
				//   //},
				];
		}
		}

	</script>
@include('footer')
<!-- </?php require_once 'footer.php'?> -->