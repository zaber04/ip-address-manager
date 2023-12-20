import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import Swal from 'sweetalert2';
import { IIPError } from '../../../interfaces/IpAddress.interfaces';
import { Component, OnDestroy, OnInit } from '@angular/core';
import { IPaginatedIpResponse, ISingleIp } from '../../../interfaces/IpAddress.interfaces';
import { Observable, Subscription, finalize } from 'rxjs';
import { SubSink } from 'subsink';
import { ActivatedRoute, Router } from '@angular/router';
import { IpHandlerService } from '../../../services/ip-handler.service';
import { PaginationService } from '../../../services/pagination.service';

@Component({
	selector: 'app-ip-create',
	templateUrl: './ip-create.component.html',
	styleUrl: './ip-create.component.scss'
})
export class IpCreateComponent {

	form: FormGroup;
	submitting = false;
	errors: IIPError = null as any;

 	labels = {
		title: 'Insert New IP',
		ipLabel: 'IP Address',
		labelLabel: 'Label',
		savelLabel: 'Save'
	};

	errorLables = {
		ipReq: 'IP Address is required',
		ipInvalid: 'IP Address is invalid',
		labelReq: 'Label is required'
	};


	constructor(
		private router: Router,
		private fb: FormBuilder,
		private ipHandlerService: IpHandlerService,
		private paginationService: PaginationService,
	) {
		const ipPattern =
			"(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)";
		this.form = this.fb.group({
			ip: ['', [Validators.required, Validators.pattern(ipPattern)]],
			label: ['', [Validators.required]],
		})
	}

	goToNewIpList() {
		this.router.navigate(['ip-addresses']);
	}

	handleSubmit() {
		this.errors = null as any;
		this.submitting = true;
		if (this.form.valid) {
			this.ipHandlerService.createIpAddress(this.form.value)
			.pipe(finalize(() => this.submitting = false))
			.subscribe({
				next: response => {
					Swal.fire({
						title: 'Successfull',
						text: 'IP is stored.',
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

}
