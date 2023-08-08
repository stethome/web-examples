import { Item } from "./item";

export class ExamModel implements Item {
  public "@id": string;

  constructor(
    _id: string,
    public uuid: string,
    public externalId: string,
    public examinedAt: string,
  ) {
    this["@id"] = _id;
  }
}
