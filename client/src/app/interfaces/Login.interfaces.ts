import { IUser } from "./User.interfaces";

export interface ILoginRequest {
	email: string;
	password: string;
}

export interface IErrorLog {
	url: string,
	param: Record<string, string>,
	body: string,
	controller: string,
	functionName: string,
	statusCode: string | number,
	message: string,
	error: object,
	ip: string
}

export interface IError {
	message: string,
	[key: string]: string;
}

export interface ILoginResponse {
	success?: boolean;
	error?: string;
	statusCode?: string | number;
	accessToken?: string;
	tokenType?: string;
	user?: IUser;
	expiresIn?: number;
	message?: string;
	errorLog?: IErrorLog;
}
