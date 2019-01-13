import { mount } from '@vue/test-utils';
import ListaView from './ListaView';

it("doesn't allow you to pass propsData", () => {
  // this doesn't work but I'd like it to
  const wrapper = mount(ListaView);

  expect(wrapper.html()).toMatchSnapshot();
});
