export interface Item {
  "@id": string;
  "uuid": string;
}

export const isItem = (data: any): data is Item => "@id" in data;
