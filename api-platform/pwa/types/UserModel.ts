import { Item } from "./item";

export class UserModel implements Item {
  public "@id": string;

  constructor(
    _id: string,
    public uuid: any,
    public email: string,
    public name: string,
    public surname: string
  ) {
    this["@id"] = _id;
  }
}
