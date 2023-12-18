import { IErrorLog } from "./Login.interfaces";
import { IUser } from "./User.interfaces";

export interface IIp {
	ip?: string;
	label: string;
}

export interface IIPResponse {
	success: boolean;
	statusCode: number;
	message: string;
	data?: IPaginatedIpResponse;
	error?: string;
	errorLog?: IErrorLog;
}

export interface IAuditResponse {
	success: boolean;
	statusCode: number;
	message: string;
	data?: IPaginatedAuditResponse;
	error?: string;
	errorLog?: IErrorLog;
}

export interface ISingleIp {
	ip: string;
	label: string;
	updated_at?: string;
	created_at?: string;
	id: string
}

export interface ILink {
	url: string;
	label: string;
	active: boolean;
}

export interface IIPError {
	message?: string;
	messages?: {
		ip_address: string[];
		label: string[];
	}
}

export interface IPaginatedData {
	current_page: number,
	first_page_url: string,
	from: number,
	last_page: number,
	last_page_url: string,
	links: [ILink],
	next_page_url: string,
	path: string,
	per_page: number,
	prev_page_url: string,
	to: number,
	total: number
}

export interface IPaginatedIpResponse extends Partial<IPaginatedData> {
	data: [ISingleIp],
}

// data
// message
// statusCode
// success


export interface IPaginatedAuditResponse extends Partial<IPaginatedData> {
	data: [IAuditTrail],
}

export interface IAuditTrail {
	id: string,
	action?: string,
	property_name?: string,
	old_data?: string,
	new_data?: string,
	user_id?: string,
	session_id?: string,
	created_at?: string,
	updated_at?: string,
	user_ip?: string,
	property_id?: string,
	table_updated?: string,
	user?: IUser
}

export enum ActionEnum {
	ARCHIVE  = 'archive',
	DELETE   = 'delete',
	INSERT   = 'insert',
	UPDATE   = 'update',
	LOGIN    = 'login',
	REGISTER = 'register',
	LOGOUT   = 'logout'
}

export interface IpAddressStore {
	list: IIPResponse,
	selectedIp: ISingleIp,
	pageSelected: number
}

export interface AuditLogStore {
	list: IAuditResponse,
	pageSelected: number
}
