import { Component, OnDestroy, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import Swal from 'sweetalert2';
import { IPaginatedIpResponse, ISingleIp } from '../../../interfaces/IpAddress.interfaces';
import { Observable, Subscription, finalize } from 'rxjs';
import { SubSink } from 'subsink';
import { ActivatedRoute, Router } from '@angular/router';
import { IpHandlerService } from '../../../services/ip-handler.service';
import { PaginationService } from '../../../services/pagination.service';

@Component({
	selector: 'app-ip-edit',
	templateUrl: './ip-edit.component.html',
	styleUrl: './ip-edit.component.scss'
})
export class IpEditComponent implements OnInit, OnDestroy {
	form: FormGroup;
	id: string;
	ipAddress$: Observable<ISingleIp> | undefined;
	triedSubmission = false;
	loading = false;
	errors: any;
	subscriptions = new SubSink();

	pageTitles = {
		ipAddresses: 'IP Address List',
		updateIp: 'Update IP',
	};

	buttons = {
		manageIps: 'Create IP',
		updateIp: 'Update IP',
	};

	labels = {
		commentLabel: 'Label',
	};

	placeholders = {
		commentLabel: 'Label',
	};

	errorMessages = {
		label: 'Label is required.',
	};

	loadingMessages = {
		pleaseWait: 'Please Wait For A While...',
	};

	constructor(
		private router: Router,
		private fb: FormBuilder,
		private ipHandlerService: IpHandlerService,
		private route: ActivatedRoute,
		private paginationService: PaginationService,
	) {
		this.id = this.route.snapshot.params['id'];

		this.form = this.fb.group({
			label: ['', [Validators.required]],
		});
	}

	ngOnInit(): void {
		this.loading = true;
		if (this.paginationService.ipAddress.selectedIp) {
			this.ipAddress$ = this.paginationService.getSelectedIp();
		} else {
			this.ipAddress$ = this.ipHandlerService.getIpAddressById(this.id);
		}

		this.subscriptions.sink = this.ipAddress$?.subscribe(result => {
			this.loading = false;
			if (result) {
				this.form.get('label')?.setValue(result?.label);
			}
		});
	}

	goToNewIpList() {
		this.router.navigate(['ip-handler']);
	}

	handleSubmit() {
		this.triedSubmission = true;
		if (this.form.valid) {
			this.ipHandlerService.updateIpAddress(this.id, this.form.value)
				.pipe(finalize(() => this.triedSubmission = false)).subscribe({
					next: response => {
						this.paginationService.setIpAddresses(null as any);
						this.paginationService.setSelectedIp(null as any);
						Swal.fire({
							title: 'Success!',
							text: 'IP updated successfully.',
							icon: 'success',
							confirmButtonText: 'ok'
						}).then(() => {
							const pageIndex = this.paginationService.getSelectedPage();
							if (pageIndex > 1) {
								this.router.navigate(['/ip-handler'], { queryParams: { page: pageIndex } });
							} else {
								this.router.navigate(['/ip-handler']);
							}
						});
					},
					error: ({ error }) => {
						this.errors = error?.errors;
					}
				});
		}
	}

	ngOnDestroy(): void {
		this.subscriptions.unsubscribe();
	}

}
