<?php echo $html->formTag('/searchs/documents'); ?>
Search: 
<?php echo $html->input('Searchs/terms'); ?>
<?php echo $html->submit(); ?>

</form>

<?php 
echo "Tổng số từ tìm thấy<br/>";
//Hiển thị tổng số từ tìm thấy
if(isset($results)): ?>
  <h1>Search results: found <?php echo count($results); ?> document(s):</h1>
  <?php foreach($results as $result): ?>
    <h3><?php echo $result->document_title; ?> - <?php echo $document->score; ?></h3>
    <p>
      <?php echo $result->document_description; ?>
      <hr>
      <a href="/documents/view/<?php echo $result->document_id; ?>">View document</a>
    </p>
  <?php endforeach; ?>
<?php endif; ?>