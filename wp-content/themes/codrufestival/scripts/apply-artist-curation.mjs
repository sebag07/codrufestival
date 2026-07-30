import { readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const themeRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const artistsPath = path.join(themeRoot, 'data', 'artists.json');

/** Verified descriptions + socials. Empty social fields mean leave unset unless filling spotify from artist record. */
const curated = {
  'balkandreea-and-the-balkan-sisters': {
    description:
      'Balkandreea (Andreea Gheorghiță) este o artistă din Botoșani care a cucerit online-ul reinterpretând melodii pe acordeon, în stil balcanic. De la viralitate pe TikTok până la scene precum Electric Castle, aduce energie, umor și o legătură vie cu muzica tradițională reimaginată pentru publicul de festival.',
    socials: {
      tiktok: 'https://www.tiktok.com/@Fata.cu.acordeonul',
      instagram: 'https://www.instagram.com/balkandreea',
    },
  },
  calinacho: {
    description:
      'Calinacho (Călin Alexandru Hasnaș) este un rapper, cântăreț și compozitor din București, activ în scena hip-hop și trap românească. De la albumul PPC la EP-uri precum ultima noapte de dragoste, combină versuri directe cu refrene memorabile și o prezență tot mai puternică pe scenele locale.',
    socials: {
      youtube: 'https://www.youtube.com/channel/UCCR6BMdAVSnxgTClKvbewkw',
      tiktok: 'https://www.tiktok.com/@calinacho',
      instagram: 'https://www.instagram.com/truecalinacho',
    },
  },
  ian: {
    description:
      'Ian (Anghel Georgian Bogdan) este unul dintre cei mai influenți artiști trap din România, cunoscut pentru hituri precum „60 De Zile”, „Urgențe” și „La san”. Albumele Slayer și Voodoo, alături de colaborările cu artiști internaționali, l-au consacrat ca fenomen al scenei urbană românești.',
    socials: {
      youtube: 'https://www.youtube.com/channel/UCXEsaxE4BOzgDKa2kG48koA',
      tiktok: 'https://www.tiktok.com/@nusuntian4',
      instagram: 'https://www.instagram.com/nusuntian',
    },
  },
  'el-cartel-reggaeton-afterparty': {
    description:
      'El Cartel Reggaeton Afterparty este conceptul de petrecere al El Cartel Events, brandului românesc care se autoproclamă cea mai mare experiență reggaeton din Europa de Est. Edițiile aduc ritmuri latino, producție de club și o atmosferă de festival, cu mii de participanți în București și în țară.',
    socials: {
      facebook: 'https://www.facebook.com/elcartelevents',
      tiktok: 'https://www.tiktok.com/@elcartel.ro',
      instagram: 'https://www.instagram.com/elcartel.ro',
    },
  },
  '4awl': {
    description:
      '4AWL este un DJ și producător din scena electronică românească, prezent la festivaluri majore precum UNTOLD, Massif și Codru. Seturile lui aduc energie de club, cu un sound orientat spre house și tech house.',
    socials: {},
  },
  'acoustic-boyz': {
    description:
      'Acoustic Boyz este un trio pop din Brașov — Denis, Pacu și Paul — construit în jurul instrumentelor live: chitară acustică, percuție și saxofon. De la clipuri virale pe TikTok la apariții la Românii au Talent, aduc un sound cald, accesibil și tot mai prezent pe scena pop românească.',
    socials: {
      youtube: 'https://www.youtube.com/@AcousticBoyz',
      tiktok: 'https://www.tiktok.com/@acousticboyz',
      instagram: 'https://www.instagram.com/acoustic.boyz',
    },
  },
  'adrian-despot-and-cezar-popescu': {
    description:
      'Adrian Despot și Cezar Popescu, din trupa Vița de Vie, urcă pe scenă în formula Acu2tic: două chitare acustice și piese emblematice ale formației, de la „Praf de stele” la „Basul și cu toba mare”. Proiectul readuce sunetul la esență, așa cum au cântat la începuturile carierei lor.',
    socials: {
      facebook: 'https://www.facebook.com/vitadevie.official',
      youtube: 'https://www.youtube.com/vitadevieofficial',
      instagram: 'https://www.instagram.com/vita_de_vie',
    },
  },
  asaway: {
    description:
      'Asaway este un proiect de muzică electronică experimentală, cu materiale precum JUNO și DOOM, într-un stil abstract orientat spre sound design și texturi digitale.',
    socials: {},
  },
  bia: {
    description:
      'Bia & Mc Geeza este un duo hip-hop din lineup-ul Codru Festival. Informații publice limitate despre proiect; prezența lor pe scenă completează zona urbană a programului.',
    socials: {},
  },
  'bob-ramanka': {
    description:
      'Bob Ramanka este proiectul solo al lui Bogdan Cotîrță Gruicin, solistul trupei timișorene Melting Dice, și combină trip hop, spoken word și elemente electronice. Pornit ca spațiu de experiment, proiectul a devenit o voce distinctă în zona muzicii electronice poetice din România.',
    socials: {
      youtube: 'https://www.youtube.com/@bobramanka',
      instagram: 'https://www.instagram.com/bob.ramanka',
    },
  },
  ciresan: {
    description:
      'Cireșan (Vlad Cireșan) este un singer-songwriter din București, asociat HaHaHa Production, cu un sound ce amestecă funk, synth-pop, rock și pop underground. Compozitor căutat pentru artiști precum Smiley, Jo și Misha Miller, a urcat pe scenele Electric Castle și Summer Well cu trupa sa live.',
    socials: {
      youtube: 'https://www.youtube.com/@vladciresan5720',
      instagram: 'https://www.instagram.com/ciresanv',
    },
  },
  'claudia-serdan-and-jimi-el-laco': {
    description:
      'Claudia Șerdan și Jimmi El Laco aduc pe scenă o fuziune între folk contemporan, sonorități acustice și prelucrări din folclorul maramureșean. Claudia — voce emoționantă cu influențe rock — și Jimmi — vioară, chitară, bouzouki; fondator Nightlosers — dau emoție în formă pură.',
    socials: {},
  },
  cypher: {
    description:
      'Cypher este un DJ din București, pionier al street dance-ului în România și co-fondator al centrului hip-hop Artizthick. Peste 20 de ani în cultura hip-hop, cu seleții eclecțice de la disco și funk la jazz, house și rap.',
    socials: {},
  },
  'damage-ctrl': {
    description:
      'DAMAGE:CTRL este un DJ din Timișoara, rezident al colectivului drum & bass NO/ID. Face parte din lineup-ul regulat al sesiunilor NO/ID la D\'Arc și Faber.',
    socials: {},
  },
  drew: {
    description:
      'Drew (Drew Tom) este un DJ din Timișoara activ în drum & bass, membru NO/ID din 2024, anterior Barbar Soundsystem (ca Dr. Drew).',
    socials: {},
  },
  emu: {
    description:
      'Emu este DJ rezident al colectivului NO/ID din Timișoara, activ pe scena locală de drum & bass.',
    socials: {},
  },
  exob: {
    description:
      'EXOB este un DJ și producător din Timișoara, co-fondator al RAVE Romania, cu un sound ancorat în tech house și deep tech minimal. Active pe scena electronică de vest, colaborează frecvent cu Tulvan.',
    socials: {
      instagram: 'https://www.instagram.com/exob.ofc',
    },
  },
  kayf: {
    description:
      'Kayf este un DJ și producător bazat în Atena, cu un sound ce îmbină melodic house, afro tech și indie dance. Seturile lui aduc energie de club și o estetică ce leagă muzica de modă, arta urbană și experiențe multisenzoriale.',
    socials: {
      instagram: 'https://www.instagram.com/kayffffffffffffff',
    },
  },
  leanu: {
    description:
      'Leanu este un DJ local din Timișoara, activ în scena underground (house, techno, electro), cu apariții la D\'Arc și evenimente Tzinah x Abor.',
    socials: {},
  },
  mandu: {
    description:
      'Mandu este un artist din lineup-ul Codru Festival. Informații publice limitate; prezența pe scenă completează programul local al festivalului.',
    socials: {},
  },
  'mini-zuchini': {
    description:
      'Mini Zuchini este un DJ din Timișoara, proiect solo de new beat, electro și italo-disco. Co-fondatoare a seriei Launmomentdat/Sabotage; gazda emisiunii Ferment Station la Black Rhino Radio.',
    socials: {
      instagram: 'https://www.instagram.com/minizucchini/',
    },
  },
  narciss: {
    description:
      'Narcis Brîndușescu (Narciss) este un DJ și producător din Timișoara, activ în tech house. Debut producție cu single-ul „Things” (84Bit Music, 2019); a deschis pentru Mark Knight, Mahony, DJ Optick și alții.',
    socials: {},
  },
  'noid-b2b-oigan': {
    description:
      'Set B2B între NO/ID, colectiv timișorean de evenimente bass-driven (drum & bass), și Oigăn (Eugen Nuțescu), cantautor român, cofondator Kumm și membru Robin and the Backstabbers.',
    socials: {
      facebook: 'https://www.facebook.com/oigansongs',
      youtube: 'https://www.youtube.com/@oigan',
      instagram: 'https://www.instagram.com/oigansongs/',
    },
  },
  paulvlj: {
    description:
      'PaulVLJ este un DJ român de techno/electronic, activ din circa 2022. Colaborări frecvente cu DJ MĂ-TA; apariții la Electric Castle, Untold, Neversea și cluburi din țară.',
    socials: {
      instagram: 'https://www.instagram.com/paulvlj/',
    },
  },
  'raul-prodan': {
    description:
      'Raul Prodan (DJ RaulL) este un DJ din Timișoara, fondator SoundMasters Events. Oferă servicii de sonorizare și entertainment, cu apariții la Codru Festival și evenimente locale.',
    socials: {},
  },
  razy: {
    description:
      'Razy este DJ rezident al colectivului NO/ID din Timișoara, activ pe scena locală de drum & bass.',
    socials: {},
  },
  revan: {
    description:
      'Revan este DJ rezident al colectivului NO/ID din Timișoara, activ pe scena locală de drum & bass și neurofunk.',
    socials: {},
  },
  'sar-casm': {
    description:
      'Robert Nicolescu (SAR.CASM) este producer și DJ din București. Fuzionează hip-hop, electro și downtempo; cunoscut pentru remixuri, bootleg-uri techno și seturi drum & bass.',
    socials: {
      instagram: 'https://www.instagram.com/sar.casm/',
    },
  },
  soulmaze: {
    description:
      'souLmaZe este un DJ din Timișoara specializat în breakbeat și house. Cunoscut pentru seturi pe vinil și apariții la Hamei și alte locații underground locale.',
    socials: {},
  },
  tano: {
    description:
      'Tano este un artist din lineup-ul Codru Festival, cu profil Spotify dedicat. Detalii publice limitate despre proiectul live.',
    socials: {},
  },
  tulvan: {
    description:
      'TULVAN este DJ și producător român de minimal și tech house, fondator al labelului For The World și co-fondator al RAVE Romania și Raveland Festival. Piese precum „Lady Luck” și „Ninety Four” au primit suport internațional.',
    socials: {
      facebook: 'https://www.facebook.com/tulvan.ofc',
      instagram: 'https://www.instagram.com/tulvan.ofc/',
    },
  },
  ufo: {
    description:
      'Florin Unguraș (UFO) este pionier al muzicii electronice timișorene din anii ’90. Fondatorul festivalului TMBase; DJ, organizator și colecționar de vinil, activ și astăzi pe scena locală.',
    socials: {
      instagram: 'https://www.instagram.com/florin_ufo/',
    },
  },
  yoshuu: {
    description:
      'Yoshuu este un producer român de neurofunk/drum & bass. EP-ul „Outset” (Blackout Music, 2024), cu colaborări kVR și Blocksberg; anterior lansat „Controlled” pe același label.',
    socials: {},
  },
  zo: {
    description:
      'Z.O.B. este o formație de punk rock și rock alternativ din București, activă din 1993, cunoscută pentru albume precum „Telenovelas”, „III” și „Deviant”. Lineup-ul Codru folosește numele scurt ZO.',
    socials: {
      facebook: 'https://www.facebook.com/ZobOfficial',
      youtube: 'https://www.youtube.com/@trupaZOB',
      instagram: 'https://www.instagram.com/trupazob/',
    },
  },
  zoleevee: {
    description:
      'Zoltan Varga (Zoleevee) este DJ și redactor muzical din Timișoara, activ din 1996 (Radio Vest). Seturi eclecțice retro-contemporane; a mixat la Revolution, Plai, Embargo, Flight și Codru Festival.',
    socials: {},
  },
  blutrina: {
    description:
      'Blutrină este o trupă de grindcore din Timișoara, activă din 2012, cu un stil comic și influențe din cultura cartoon. Au lansat albume precum „Looney Fuckin\' Grind” (2016) și „DiscoBallz” (2018).',
    socials: {
      facebook: 'https://www.facebook.com/Blutrina/',
      instagram: 'https://www.instagram.com/blutrina',
    },
  },
  'disko-anksyete': {
    description:
      'Disko Anksyete este o serie de petreceri post-punk, darkwave și gothic din București, curată de DJ Corina Sucarov la Manasia Hub (ex. Vampires of the Night, Masked Victorian Ball).',
    socials: {
      instagram: 'https://www.instagram.com/corinasucarov/',
    },
  },
  doomnezeu: {
    description:
      'Doomnezeu este o trupă românească de doom metal, fondată în 2019, care combină riffuri lente cu tematică inspirată de tradiții ortodoxe și ritualuri. Numele este un joc de cuvinte între „doom” și „Dumnezeu”.',
    socials: {
      facebook: 'https://www.facebook.com/doomnezeuband',
      youtube: 'https://www.youtube.com/@nihil_sine_doomnezeu',
      instagram: 'https://www.instagram.com/nihil_sine_doomnezeu/',
    },
  },
  dordeduh: {
    description:
      'Dordeduh este un proiect de atmospheric/progressive folk black metal din Timișoara, fondat în 2009 de Hupogrammos și Sol Faur după despărțirea de Negură Bunget. Albumul „Har” (2021) este cel mai recent LP de studio.',
    socials: {
      facebook: 'https://www.facebook.com/Dordeduh',
      twitter: 'https://twitter.com/Dordeduh',
      instagram: 'https://www.instagram.com/dordeduhband/',
    },
  },
  'emo-reunion': {
    description:
      'Emo Reunion este cea mai mare serie de petreceri emo, pop-punk, rock și alternative din România. DJ sets și cover band cu hituri My Chemical Romance, Fall Out Boy, Paramore și altele.',
    socials: {
      instagram: 'https://www.instagram.com/emoreunion/',
    },
  },
  nocturn: {
    description:
      'Nocturn este un concept de nightlife curat (nocturnsocial.ro), cu DJ selectați și atmosferă immersive. Ediții notabile la Avi Garden și NYE Nocturn.',
    socials: {},
  },
  'ordinul-negru': {
    description:
      'Ordinul Negru este o trupă de black metal ocult din Timișoara, activă din 2004, cu nouă albume de studio, inclusiv „Dodekatemoria” (2024), lansat prin Loud Rage Music.',
    socials: {
      facebook: 'https://www.facebook.com/ordinulnegru',
      instagram: 'https://www.instagram.com/ordinulnegruofficial/',
    },
  },
  'sear-bliss': {
    description:
      'Sear Bliss este o trupă maghiară de atmospheric black metal din Szombathely, activă din 1993, remarcabilă prin integrarea trombonei, trumpetei și altor instrumente de suflat în sunetul black metal.',
    socials: {
      facebook: 'https://www.facebook.com/searblissband',
      youtube: 'https://www.youtube.com/@SearBlissOfficial',
      instagram: 'https://www.instagram.com/searbliss',
    },
  },
  'sur-austru': {
    description:
      'Sur Austru este o trupă românească de atmospheric folk/black metal din Arad, formată în 2018 de foști membri ai Negură Bunget. Tematica explorează folclorul și natura, cu albume precum „Meteahna timpurilor” și „Datura străhiarelor”.',
    socials: {
      facebook: 'https://www.facebook.com/SurAustruOfficial',
      youtube: 'https://www.youtube.com/channel/UCHnSufXHTWrxpb9HjJIXZpw',
      instagram: 'https://www.instagram.com/suraustru',
    },
  },
};

const defaultSocials = {
  facebook: '',
  youtube: '',
  tiktok: '',
  twitter: '',
  instagram: '',
  spotify: '',
};

function mergeSocials(existing, patch, spotifyUrl) {
  const merged = { ...defaultSocials, ...(existing ?? {}), ...(patch ?? {}) };

  if (spotifyUrl && !merged.spotify) {
    merged.spotify = spotifyUrl;
  }

  return merged;
}

const source = JSON.parse(await readFile(artistsPath, 'utf8'));
let updated = 0;

const artists = (source.artists ?? []).map((artist) => {
  const entry = curated[artist.id];
  if (!entry) {
    return artist;
  }

  updated += 1;

  return {
    ...artist,
    description: entry.description,
    socials: mergeSocials(artist.socials, entry.socials, artist.spotify_url || ''),
  };
});

await writeFile(
  artistsPath,
  `${JSON.stringify(
    {
      ...source,
      updated_at: new Date().toISOString(),
      artists,
    },
    null,
    2,
  )}\n`,
);

console.log(`Applied curation to ${updated} artists in ${path.relative(process.cwd(), artistsPath)}`);
