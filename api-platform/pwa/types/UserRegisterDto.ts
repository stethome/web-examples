
export class UserRegisterDto {
  constructor(
    public email: string,
    public password: string,
    public name: string,
    public surname: string
  ) {
  }
}
