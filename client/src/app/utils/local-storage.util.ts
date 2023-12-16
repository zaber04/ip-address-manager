// src/app/utils/local-storage.util.ts

export class LocalStorageUtil {
	public static Supported(): boolean {
		return typeof Storage !== 'undefined';
	}

	public static set(key: string, value: any, ttl = 0): void {
		const item = {
			value,
			canExpire: ttl >= 0,
			expireAt: (new Date().getTime()) / 1000 + ttl,
		};

		localStorage.setItem(key, JSON.stringify(item));
	}

	public static get(key: string): any {
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

	public static clearAll(): void {
		localStorage.clear();
		console.log('LocalStorageUtil: Localstorage has been cleared');
	}

	public static clear(key: string): void {
		localStorage.removeItem(key);
		console.log(`LocalStorageUtil: ${key} has been removed from localstorage`);
	}
}
