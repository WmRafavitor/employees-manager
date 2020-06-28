import router from '@/router';
import authenticationService from '@/services/authentication';

function tryAccessAnonimously(to, next) {
  if (!to.meta.allowAnonymous) {
    next({
      name: 'login',
    });
  } else {
    next();
  }
}

/**
 * Manage the access of the logged users and routes with allowed anonymous access.
 * @param {*} to
 * @param {*} from
 * @param {*} next
 */
function handleAuthentication(to, from, next) {
  if (!authenticationService.hasTokenValid()) {
    tryAccessAnonimously(to, next);
  } else {
    next();
  }
}

export default {
  install() {
    router.beforeEach(handleAuthentication);
  },
};