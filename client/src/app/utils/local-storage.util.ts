export class LocalStorageUtil {
	private static isBrowser(): boolean {
		return typeof window !== 'undefined';
	}

	public static Supported(): boolean {
		return LocalStorageUtil.isBrowser() && typeof Storage !== 'undefined';
	}

	public static set(key: string, value: any, ttl = 0): void {
		if (LocalStorageUtil.isBrowser()) {
			const item = {
				value,
				canExpire: ttl >= 0,
				expireAt: (new Date().getTime()) / 1000 + ttl,
			};

			localStorage.setItem(key, JSON.stringify(item));
		}
	}

	public static get(key: string): any {
		if (LocalStorageUtil.isBrowser()) {
			const itemStr = localStorage.getItem(key);

			if (!itemStr) {
				return null;
			}

			const item = JSON.parse(itemStr);

			if (item.canExpire && (new Date().getTime()) / 1000 > item.expireAt) {
				localStorage.removeItem(key);
				return null;
			}

			return item.value;
		}

		return null;
	}

	public static clearAll(): void {
		if (LocalStorageUtil.isBrowser()) {
			localStorage.clear();
			console.log('LocalStorageUtil: Localstorage has been cleared');
		}
	}

	public static clear(key: string): void {
		if (LocalStorageUtil.isBrowser()) {
			localStorage.removeItem(key);
			console.log(`LocalStorageUtil: ${key} has been removed from localstorage`);
		}
	}
}
