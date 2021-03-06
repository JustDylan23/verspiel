import TimeAgo from 'javascript-time-ago';

// English.
import en from 'javascript-time-ago/locale/en';

TimeAgo.addDefaultLocale(en);
const timeAgo = new TimeAgo('en-US');

export const formatDateString = (dateString) =>
  timeAgo.format(new Date(dateString), 'twitter-now');
