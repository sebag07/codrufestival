<?php /*  Template Name: General Info  */ ?>
<?php get_header('codru2023live'); ?>

<?php $filter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : "program"; ?>

<script>

var $tabsToDropdown;

function generateDropdownMarkup(container) {
    const $navWrapper = container.find(".nav-wrapper");
    const $navPills = container.find(".nav-pills");
    const firstTextLink = $navPills.find("li a.active").text();
    const $items = $navPills.find("li");
    const markup = `
    <div class="dropdown d-md-none">
      <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        ${firstTextLink}
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> 
        ${generateDropdownLinksMarkup($items)}
      </div>
    </div>
  `;
    $navWrapper.prepend(markup);
}

function generateDropdownLinksMarkup(items) {
    let markup = "";
    items.each(function() {
        const textLink = jQuery(this).find("a").text();
        console.log(textLink);
        markup += `<a class="dropdown-item" href="#">${textLink}</a>`;
    });

    return markup;
}

function showDropdownHandler(e) {
    // works also
    //const $this = $(this);
    const $this = jQuery(e.target);
    const $dropdownToggle = $this.find(".dropdown-toggle");
    const dropdownToggleText = $dropdownToggle.text().trim();
    const $dropdownMenuLinks = $this.find(".dropdown-menu a");
    const dNoneClass = "d-none";
    $dropdownMenuLinks.each(function() {
        const $this = $(this);
        if ($this.text() == dropdownToggleText) {
            $this.addClass(dNoneClass);
        } else {
            $this.removeClass(dNoneClass);
        }
    });
}

function clickHandler(e) {
    e.preventDefault();
    const $this = jQuery(this);
    const index = $this.index();
    const text = $this.text();
    $this.closest(".dropdown").find(".dropdown-toggle").text(`${text}`);
    $this
        .closest($tabsToDropdown)
        .find(`.nav-pills li:eq(${index}) a`)
        .tab("show");
}

function shownTabsHandler(e) {
    // works also
    //const $this = $(this);
    const $this = jQuery(e.target);
    const index = $this.parent().index();
    const $parent = $this.closest($tabsToDropdown);
    const $targetDropdownLink = $parent.find(".dropdown-menu a").eq(index);
    const targetDropdownLinkText = $targetDropdownLink.text();
    $parent.find(".dropdown-toggle").text(targetDropdownLinkText);
}

jQuery(document).ready(function() {
    $tabsToDropdown = jQuery(".tabs-to-dropdown");
    $tabsToDropdown.each(function() {
        console.log("aiic intri?")
        const $this = jQuery(this);
        const $pills = $this.find('a[data-toggle="pill"]');

        console.log($pills);

        generateDropdownMarkup($this);

        const $dropdown = $this.find(".dropdown");
        const $dropdownLinks = $this.find(".dropdown-menu a");

        $dropdown.on("show.bs.dropdown", showDropdownHandler);
        $dropdownLinks.on("click", clickHandler);
        $pills.on("shown.bs.tab", shownTabsHandler);

    });
})
</script>

<div class="container-xl generalInfoPage pt-5 pb-5">
    <h1 class="pt-5 pb-4 text-center"><?php echo get_the_title(); ?></h1>
    <div class="tabs-to-dropdown row pt-3">
        <div class="nav-wrapper col-lg-4 col-md-4 col-12">
            <ul class="nav nav-pills d-none d-md-flex" id="pills-tab" role="tablist">
                <?php if ( have_rows( 'general_info_repeater', 'options' ) ): ?>

                <?php while( have_rows( 'general_info_repeater', 'options' ) ) : the_row(); ?>

                <?php if( $generalInfoBtnText = get_sub_field('general_info_button_text', 'options') ): ?> 
                    
                   <?php  $generalInfoBtnUrl = get_sub_field('general_info_button_url', 'options'); ?>

                    <li class='nav-item' role='presentation'>
                      <a class='nav-link <?php if($filter == $generalInfoBtnUrl) echo "active"; ?>' id='pills-<?php echo $generalInfoBtnUrl; ?>-tab' data-toggle='pill' href='#pills-<?php echo $generalInfoBtnUrl; ?>' role='tab' aria-controls='pills-<?php echo $generalInfoBtnUrl; ?>' aria-selected='false'><?php echo $generalInfoBtnText; ?></a>
                    </li>            
                
                
                <?php endif;?>

                <?php endwhile; ?>

                <?php endif; ?>
            </ul>
        </div>

        <div class="tab-content col-lg-8 col-md-8 col-12" id="pills-tabContent">
            <?php if ( have_rows( 'general_info_repeater', 'options' ) ): ?>

            <?php while( have_rows( 'general_info_repeater', 'options' ) ) : the_row(); ?>

            <?php if( $generalInfoContent = get_sub_field('general_info_content_tab', 'options') ): ?>
                
                <?php $generalInfoBtnUrl = get_sub_field('general_info_button_url', 'options'); ?>

                <div class='tab-pane fade general-info-content <?php if($filter == $generalInfoBtnUrl) echo "active show"; ?>' id='pills-<?php echo $generalInfoBtnUrl; ?>' role='tabpanel' aria-labelledby='pills-<?php echo $generalInfoBtnUrl; ?>-tab'><?php echo $generalInfoContent; ?></div>

            <?php endif; ?>
            <?php endwhile; ?>

            <?php endif; ?>
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.min.js"
    integrity="sha512-fHY2UiQlipUq0dEabSM4s+phmn+bcxSYzXP4vAXItBvBHU7zAM/mkhCZjtBEIJexhOMzZbgFlPLuErlJF2b+0g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php get_footer('codru2023live'); ?>