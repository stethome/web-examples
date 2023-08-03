import {useFormik} from "formik";
import {fetch} from "../../utils/dataAccess";
import {UserRegisterDto} from "../../types/UserRegisterDto";
import {FormError, FormStatus} from "../common/form/form";
import {router} from "next/client";

const RegisterForm = () => {
  const formik = useFormik<UserRegisterDto>({
    initialValues: {
      email: '',
      name: '',
      surname: '',
      password: '',
    },
    onSubmit: (values, formik) => {
        fetch<UserRegisterDto>('/api/register', {
          method: 'POST',
          body: JSON.stringify(values),
        })
          .catch(error => {
            formik.setStatus({
              isValid: false,
              msg: `${error.message}`,
            });
            if ("fields" in error) {
              formik.setErrors(error.fields);
            }
          })
          .then(() => window.location.href = '/app/')
    },
  });

  return (
    <form onReset={formik.handleReset} onSubmit={formik.handleSubmit} className="grid grid-cols-1 gap-6 max-w-md">
      <label>
        Email Address
        <input
          className="w-full"
          id="email"
          type="email"
          {...formik.getFieldProps('email')}
        />
        <FormError error={formik.errors.email} />
      </label>

      <label>
        Password
        <input
          className="w-full"
          id="password"
          type="password"
          {...formik.getFieldProps('password')}
        />
        <FormError error={formik.errors.password} />
      </label>

      <label>
        Name
        <input
          className="w-full"
          id="name"
          type="text"
          {...formik.getFieldProps('name')}
        />
        <FormError error={formik.errors.name} />
      </label>

      <label>
        Surname
        <input
          className="w-full"
          id="surname"
          type="text"
          {...formik.getFieldProps('surname')}
        />
        <FormError error={formik.errors.surname} />
      </label>

      <FormStatus status={formik.status} />

      <button type="submit" className="bg-blue-500 hover:bg-blue-700 font-bold text-white py-2 px-4">Submit</button>
    </form>
  );
}
 export default RegisterForm;
