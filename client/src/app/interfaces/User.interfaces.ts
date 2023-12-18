export interface IUser extends IUserBasic {
    email_verified_at?: string,
    contact?: string,
    address?: string,
    created_at?: string,
    updated_at?: string,
    deleted_at?: string,
}

export interface IUserBasic {
	id: string,
	email: string,
    first_name: string,
    last_name: string
}
