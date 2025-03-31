<?php

use app\helpers\Html;
use app\models\Member;
?>

<div class="card card-custom card-stretch gutter-b">
	<div class="card-header border-0 pt-6">
		<h3 class="card-title align-items-start flex-column">
			<span class="card-label font-weight-bolder font-size-h4 text-dark-75">
				Recent Members
			</span>
			<span class="text-muted mt-3 font-weight-bold font-size-lg">
				Freshly added members
			</span>
		</h3>
		<div class="card-toolbar">
			<div class="dropdown dropdown-inline" data-toggle="tooltip" title="" data-placement="left" data-original-title="Quick actions">
				<a href="#" class="btn btn-icon-primary btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="svg-icon svg-icon-lg">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1">
								<rect x="14" y="9" width="6" height="6" rx="3" fill="black"></rect>
								<rect x="3" y="9" width="6" height="6" rx="3" fill="black" fill-opacity="0.7"></rect>
							</g>
						</svg>
					</span>
				</a>
				<div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
					<ul class="navi navi-hover navi-active navi-accent">
						<li class="navi-header font-weight-bold py-5">
							<span class="font-size-lg">Quick Actions:</span>
							<i class="flaticon2-information icon-md text-muted"></i>
						</li>
						<li class="navi-separator mb-3 opacity-70"></li>
						
						<li class="navi-item">
							<a href="<?= (new Member())->indexUrl ?>" class="navi-link">
								<span class="navi-icon">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24"></rect>
												<path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3"></path>
												<path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000"></path>
											</g>
										</svg>
									</span>
								</span>
								<span class="navi-text">Members List</span>
							</a>
						</li>
						<li class="navi-item">
							<a href="<?= (new Member())->createUrl ?>" class="navi-link">
								<span class="navi-icon">
									<span class="svg-icon svg-icon-md svg-icon-primary">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<polygon points="0 0 24 0 24 24 0 24"></polygon>
												<path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
												<path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
											</g>
										</svg>
									</span>
								</span>
								<span class="navi-text">Add Member</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body pt-5">
		<?= Html::if(($members = Member::recent()) != null, function() use($members) {
			return Html::foreach($members, function($member) {
				return <<< HTML
					<div class="d-flex align-items-center mb-6">
						<div class="symbol symbol-35 flex-shrink-0 mr-3">
							{$member->image}
						</div>
						<div class="d-flex flex-wrap flex-row-fluid">
							<div class="d-flex flex-column pr-5 flex-grow-1">
								<a href="{$member->viewUrl}" class="text-dark text-hover-primary mb-1 font-weight-bolder font-size-lg">
									{$member->fullname}
								</a>
								<span class="text-muted font-weight-bold">
									{$member->widgetTags}
								</span>
							</div>

							<div class="d-flex align-items-center py-2">
								<a title="View Profile" data-toggle="tooltip" href="{$member->viewUrl}" class="btn btn-icon btn-light btn-sm">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<polygon points="0 0 24 0 24 24 0 24"></polygon>
												<path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-270.000000) translate(-12.000003, -11.999999)"></path>
											</g>
										</svg>
									</span>
								</a>
							</div>
						</div>
					</div>
				HTML;
			});
		}, <<< HTML
			<div class="text-center">
				<span class="svg-icon svg-icon-primary svg-icon-10x">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<rect x="0" y="0" width="24" height="24"/>
						<path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" fill="#000000" opacity="0.3"/>
						</g>
					</svg>
				</span>
				<h4 class="">No Recent members.</h4>
			</div>
		HTML) ?>
	</div>
</div>